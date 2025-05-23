<?php

namespace Sholokhov\Exchange;

use Throwable;
use Exception;
use ArrayIterator;
use ReflectionException;

use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Bootstrap\Validator;
use Sholokhov\Exchange\Fields\FieldInterface;
use Sholokhov\Exchange\Repository\Types\Memory;
use Sholokhov\Exchange\Repository\RepositoryInterface;
use Sholokhov\Exchange\Validators\ValidatorInterface;
use Sholokhov\Exchange\Helper\Entity;
use Sholokhov\Exchange\Helper\FieldHelper;
use Sholokhov\Exchange\Helper\LoggerHelper;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Messages\Type\Error;
use Sholokhov\Exchange\Messages\Type\DataResult;
use Sholokhov\Exchange\Prepares\Chain;
use Sholokhov\Exchange\Prepares\PrepareInterface;
use Sholokhov\Exchange\Target\Attributes\Validate;
use Sholokhov\Exchange\Target\Attributes\MapValidator;
use Sholokhov\Exchange\Target\Attributes\BootstrapConfiguration;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;

#[MapValidator]
abstract class Exchange extends Application
{
    use LoggerAwareTrait;

    public const BEFORE_RUN = 'beforeRun';
    public const AFTER_RUN = 'afterRun';
    public const BEFORE_ADD = 'beforeAdd';
    public const AFTER_ADD = 'afterAdd';
    public const BEFORE_UPDATE = 'beforeUpdate';
    public const AFTER_UPDATE = 'afterUpdate';
    public const BEFORE_IMPORT_ITEM = 'beforeImportItem';
    public const AFTER_IMPORT_ITEM = 'afterImportItem';

    /**
     * Карта обмена
     *
     * @var array
     */
    private array $map = [];

    /**
     * Время запуска обмена
     *
     * @var int
     */
    protected int $dateUp = 0;

    /**
     * Хранилище данных текущего обмена
     *
     * @var RepositoryInterface
     */
    protected readonly RepositoryInterface $repository;

    /**
     * Добавление нового элемента сущности
     *
     * @param array $item
     * @return ResultInterface
     */
    abstract protected function add(array $item): ResultInterface;

    /**
     * Обновление элемента сущности
     *
     * @param array $item
     * @return ResultInterface
     */
    abstract protected function update(array $item): ResultInterface;

    /**
     * Проверка наличия элемента сущности
     *
     * @param array $item
     * @return bool
     */
    abstract protected function exists(array $item): bool;

    /**
     * Деактивация элементов сущности, которые не пришли в обмене
     *
     * @return void
     */
    protected function deactivate(): void
    {
    }

    /**
     * Запуск обмена
     *
     * @param iterable $source
     * @return ResultInterface
     * @throws ReflectionException
     */
    final public function execute(iterable $source): ResultInterface
    {
        $dataResult = [];
        $result = $this->validate();

        if (!$result->isSuccess()) {
            return $result;
        }

        $this->dateUp = time();

        (new Event(self::BEFORE_RUN, ['exchange' => $this]))->send();

        try {
            foreach ($source as $item) {
                if (!is_array($item)) {
                    $this->logger?->warning('The source value is not an array: ' . json_encode($item));
                    continue;
                }

                $action = $this->action($item);
                if (!$action->isSuccess()) {
                    $result->addErrors($action->getErrors());
                }

                if ($data = $action->getData()) {
                    $dataResult[] = $data;
                }
            }

        } catch (Throwable $throwable) {
            $result->addError(Error::createFromThrowable($throwable));
            $this->logger?->critical(LoggerHelper::exceptionToString($throwable));
        }

        (new Event(self::AFTER_RUN, ['exchange' => $this]))->send();

        if ($this->getOptions()->get('deactivate')) {
            $this->deactivate();
        }

        $this->dateUp = 0;

        return $result->setData($dataResult);
    }

    /**
     * ID сайта, которому принадлежит обмен
     *
     * @return string
     */
    public function getSiteID(): string
    {
        return (string)$this->getOptions()->get('site_id');
    }

    /**
     * Получение карты обмена
     *
     * @return FieldInterface[]
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * Указание карты данных обмена
     *
     * @param array $map
     * @return Exchange
     */
    public function setMap(array $map): static
    {
        $this->map = $map;
        return $this;
    }

    /**
     * Добавить преобразователь данных обмена
     *
     * @param PrepareInterface $prepare
     * @return $this
     */
    public function addPrepared(PrepareInterface $prepare): self
    {
        $this->getPrepares()->add($prepare);
        return $this;
    }

    /**
     * Получение цепочки преобразователей данных
     *
     * @final
     * @return Chain
     */
    final protected function getPrepares(): Chain
    {
        return $this->repository->get('prepares');
    }

    /**
     * Проверка возможности запуска обмена данных
     *
     * @return ResultInterface
     */
    protected function validate(): ResultInterface
    {
        $bootstrap = new Validator($this);
        return $bootstrap->run();
    }

    /**
     * Получение свойства отвечающего за идентификацию значения
     *
     * @final
     * @return FieldInterface
     * @throws Exception
     */
    final protected function getPrimaryField(): FieldInterface
    {
        foreach ($this->getMap() as $field) {
            if ($field->isPrimary()) {
                return $field;
            }
        }

        throw new Exception("No key field found");
    }

    /**
     * Вызов действия над элементом источника
     *
     * @param array $item
     * @return ResultInterface
     */
    private function action(array $item): ResultInterface
    {
        (new Event(self::BEFORE_IMPORT_ITEM, ['exchange' => $this, 'item' => &$item]))->send();

        $prepareResult = $this->prepared($item);

        if (!$prepareResult->isSuccess()) {
            return $prepareResult;
        }

        $item = $prepareResult->getData();

        if ($this->exists($item)) {
            $event = new Event(self::BEFORE_UPDATE, ['exchange' => $this, 'item' => &$item]);
            $event->send();

            foreach ($event->send() as $eventResult) {
                if ($eventResult->getStatus() !== EventResult::SUCCESS) {
                    $this->logger?->debug('The updating of the element was rejected by the event: ' . json_encode($item));
                    return new DataResult;
                }
            }

            $result = $this->update($item);
            (new Event(self::AFTER_UPDATE, ['exchange' => $this, 'item' => $item, 'result' => $result]))->send();
        } else {
            $event = new Event(self::BEFORE_ADD, ['exchange' => $this, 'item' => &$item]);

            foreach ($event->send() as $eventResult) {
                if ($eventResult->getStatus() !== EventResult::SUCCESS) {
                    $this->logger?->debug('The creation of the element was rejected by the event: ' . json_encode($item));
                    return new DataResult;
                }
            }

            $result = $this->add($item);
            (new Event(self::AFTER_ADD, ['exchange' => $this, 'item' => $item, 'result' => $result]))->send();
        }

        (new Event(self::AFTER_IMPORT_ITEM, ['exchange' => $this, 'item' => $item, 'result' => $result]))->send();

        return $result;
    }

    /**
     * Преобразование значения
     *
     * @param array $item
     * @return ResultInterface
     */
    private function prepared(array $item): ResultInterface
    {
        $result = new DataResult;
        $map = $this->getMap();
        $data = [];

        foreach ($map as $field) {
            $value = FieldHelper::getValue($item, $field);
            $value = $this->normalize($value, $field);

            if ($field->getTarget()) {
                $targetResult = $this->runTarget($value, $field);
                if (!$targetResult->isSuccess()) {
                    $result->addErrors($targetResult->getErrors());
                }
            } else {
                $value = $this->getPrepares()->prepare($value, $field);
            }

            $data[$field->getCode()] = $value;
        }

        $result->setData($data);

        return $result;
    }

    /**
     * Вызов вложенного импорта указанного в свойстве
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return ResultInterface
     */
    private function runTarget(mixed $value, FieldInterface $field): ResultInterface
    {
        $target = $field->getTarget();

        if ($this->logger && $target instanceof LoggerAwareInterface) {
            $target->setLogger($this->logger);
        }

        if ($this->logger && $target instanceof LoggerAwareInterface) {
            $target->setLogger($this->logger);
        }

        $source = new ArrayIterator([$value]);
        $result = $target->execute($source);

        return FieldHelper::normalizeValue($result, $field);
    }

    /**
     * Нормализация импортированного значения
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return mixed
     */
    private function normalize(mixed $value, FieldInterface $field): mixed
    {
        $value = FieldHelper::normalizeValue($value, $field);

        foreach ($field->getNormalizers() as $validator) {
            $value = call_user_func_array($validator, [$value, $field]);
        }

        return $value;
    }

    /**
     * Валидация карты обмена
     *
     * @return ResultInterface
     */
    #[Validate]
    private function mapValidate(): ResultInterface
    {
        return $this->repository
            ->get('map_validator')
            ->validate($this->getMap());
    }

    /**
     * Инициализация хранилища дополнительных данных обмена
     *
     * @return void
     */
    #[BootstrapConfiguration]
    private function bootstrapRepository(): void
    {
        $this->repository = new Memory;
        $this->repository->set('prepares', new Chain);
    }

    /**
     * Инициализация механизма валидации карты обмена
     *
     * @return void
     * @throws ReflectionException
     */
    #[BootstrapConfiguration]
    private function bootstrapMap(): void
    {
        /** @var MapValidator $attribute */
        $attribute = Entity::getAttributeChain($this, MapValidator::class);
        $validator = $attribute->getEntity();

        if (!is_subclass_of($validator, ValidatorInterface::class)) {
            throw new Exception('Validator class must be subclass of ' . ValidatorInterface::class);
        }

        $this->repository->set('map_validator', new $validator);
    }
}