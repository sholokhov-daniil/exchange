<?php

namespace Sholokhov\Exchange\Normalizers\File;

use ReflectionException;

use Sholokhov\Exchange\Target\File;
use Sholokhov\Exchange\Messages\ResultInterface;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;

/**
 * Приведение ссылки на файл в соответствии с требованиями различных сущностей
 */
abstract class AbstractNormalizer implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Карта импорта файла
     * @var array
     */
    private array $map = [];

    /**
     * Общая конфигурация обмена
     *
     * @var array
     */
    private array $options = [];

    /**
     * Приведение файла необходимый формат
     *
     * @param mixed $source
     * @return array
     */
    abstract public function normalize(mixed $source): array;

    /**
     * Указание карты импорта файла
     *
     * @param array $map
     * @return $this
     */
    public function setMap(array $map): self
    {
        $this->map = $map;
        return $this;
    }

    /**
     * Указание общей конфигурации
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Импортирование данных
     *
     * @param mixed $source
     * @param array $options
     * @return ResultInterface
     * @throws ReflectionException
     */
    protected function execute(mixed $source, array $options = []): ResultInterface
    {
        if (!is_array($source)) {
            $source = $source ? [$source] : [];
        }

        $exchange = new File(array_merge($this->options, $options));
        $exchange->setMap($this->map);

        if ($this->logger) {
            $exchange->setLogger($this->logger);
        }

        return $exchange->execute($source);
    }
}