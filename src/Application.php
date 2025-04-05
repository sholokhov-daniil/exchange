<?php

namespace Sholokhov\Exchange;

use Exception;
use ReflectionException;

use Sholokhov\Exchange\Helper\Entity;
use Sholokhov\Exchange\Repository\RepositoryInterface;
use Sholokhov\Exchange\Target\Attributes\CacheContainer;
use Sholokhov\Exchange\Target\Attributes\OptionsContainer;

#[OptionsContainer]
#[CacheContainer]
abstract class Application implements ExchangeInterface
{
    /**
     * Конфигурация обмена
     *
     * @var RepositoryInterface
     */
    private readonly RepositoryInterface $options;

    /**
     * Кэш данных, которые принимали участие в обмене
     *
     * @todo Потом поменять подход
     * @var RepositoryInterface
     */
    protected readonly RepositoryInterface $cache;

    /**
     * @param array $options Конфигурация объекта
     * @throws ReflectionException
     */
    public function __construct(array $options = [])
    {
        $this->options = $this->makeOptionRepository($options);
        $this->cache = $this->makeCacheRepository();
        $this->configure();
    }

    /**
     * Конфигурация текущего обмена
     *
     * @return void
     */
    protected function configure(): void
    {
    }

    /**
     * Предназначен для преобразования(обработки) конфигураций перед сохранением
     *
     * @param array $options
     * @return array
     */
    protected function normalizeOptions(array $options): array
    {
        return $options;
    }

    /**
     * Конфигурация обмена
     *
     * @return RepositoryInterface
     */
    protected function getOptions(): RepositoryInterface
    {
        return $this->options;
    }

    /**
     * Инициализация хранилища настроек обмена
     *
     * @param array $options
     * @return RepositoryInterface
     * @throws ReflectionException
     * @throws Exception
     */
    private function makeOptionRepository(array $options = []): RepositoryInterface
    {
        /** @var OptionsContainer $attribute */
        $attribute = Entity::getAttribute($this, OptionsContainer::class) ?: Entity::getAttribute(self::class, OptionsContainer::class);

        $entity = $attribute->getEntity();

        if (!is_subclass_of($entity, RepositoryInterface::class)) {
            throw new Exception('The exchange configuration repository is not a subclass of ' . RepositoryInterface::class);
        }

        return new $entity($options);
    }

    /**
     * Инициализация хранилища кэша
     *
     * @return RepositoryInterface
     * @throws ReflectionException
     * @throws Exception
     */
    private function makeCacheRepository(): RepositoryInterface
    {
        /** @var CacheContainer $attribute */
        $attribute = Entity::getAttribute($this, CacheContainer::class) ?: Entity::getAttribute(self::class, CacheContainer::class);
        $entity = $attribute->getEntity();

        if (!is_subclass_of($entity, RepositoryInterface::class)) {
            throw new Exception('The exchange cache repository is not a subclass of ' . RepositoryInterface::class);
        }

        $options = $this->options->get('cache') ?: [];

        return new $entity($options);
    }
}