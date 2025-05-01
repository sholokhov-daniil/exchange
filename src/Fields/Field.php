<?php

namespace Sholokhov\Exchange\Fields;

use Sholokhov\Exchange\ExchangeInterface;
use Sholokhov\Exchange\Repository\RepositoryInterface;
use Sholokhov\Exchange\Repository\Types\Memory;

/**
 * Описание структуры и логики работы со свойством
 */
class Field implements FieldInterface
{
    /**
     * Конфигурация свойства
     *
     * @var RepositoryInterface
     */
    private readonly RepositoryInterface $container;

    /**
     * Является идентификационным полем
     *
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->getContainer()->get('key_field', false);
    }

    /**
     * Установить флаг идентификационного поля
     *
     * @param bool $value
     * @return $this
     */
    public function setPrimary(bool $value = true): self
    {
        $this->getContainer()->set('key_field', $value);
        return $this;
    }

    /**
     * Получение пути размещения значения свойства
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getContainer()->get('path', '');
    }

    /**
     * Установка пути размещения значения свойства
     *
     * @param string $path
     * @return static
     */
    public function setPath(string $path): self
    {
        $this->getContainer()->set('path', $path);
        return $this;
    }

    /**
     * Код свойства в которое будет записано значение
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->getContainer()->get('code', '');
    }

    /**
     * Установка кода в который необходимо записать значение
     *
     * @param string $code
     * @return static
     */
    public function setCode(string $code): self
    {
        $this->getContainer()->set('code', $code);
        return $this;
    }

    /**
     * Получение цели значения свойства
     *
     * @return ExchangeInterface|null
     */
    public function getTarget(): ?ExchangeInterface
    {
        return $this->getContainer()->get('target');
    }

    /**
     * Установка цели значения свойства
     *
     * @param ExchangeInterface $target
     * @return static
     */
    public function setTarget(ExchangeInterface $target): self
    {
        $this->getContainer()->set('target', $target);
        return $this;
    }

    /**
     * Значение является множественным
     *
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->getContainer()->get('multiple', false);
    }

    /**
     * Установка, что значение является множественным
     *
     * @param bool $multiple
     * @return static
     */
    public function setMultiple(bool $multiple = true): self
    {
        $this->getContainer()->set('multiple', $multiple);
        return $this;
    }

    /**
     * Получение дочернего элемента
     *
     * @return FieldInterface|null
     */
    public function getChildren(): ?FieldInterface
    {
        return $this->getContainer()->get('children', null);
    }

    /**
     * Получение нормализаторов значения свйоства
     *
     * @return callable[]
     */
    public function getNormalizers(): array
    {
        return $this->getContainer()->get('normalizers', []);
    }

    /**
     * Указание нормализаторов свойства
     *
     * @param array $normalizers
     * @return $this
     */
    public function setNormalizers(array $normalizers): self
    {
        $this->getContainer()->set('normalizers', []);
        array_walk($normalizers, [$this, 'addNormalizer']);

        return $this;
    }

    /**
     * Добавление нормализатора данных
     *
     * @param callable $callback
     * @return $this
     */
    public function addNormalizer(callable $callback): self
    {
        $data = $this->getNormalizers();
        $data[] = $callback;
        $this->getContainer()->set('normalizers', $data);
        return $this;
    }

    /**
     * Установка дочернего элемента
     *
     * Описание свойства, которое имеет итерационные значения на своем пути
     * Подходит, если необходимо получить ID изображения
     * <item>
     *     <name>NAME</name>
     *     <images>
     *          <image id="35" />
     *          <image id="35" />
     *      </images>
     * </item>
     *
     * @param FieldInterface $children
     * @return $this
     */
    public function setChildren(FieldInterface $children): self
    {
        $this->getContainer()->set('children', $children);
        return $this;
    }

    /**
     * Получение хранилище данных
     *
     * @final
     * @return RepositoryInterface
     */
    final protected function getContainer(): RepositoryInterface
    {
        return $this->container ??= new Memory();
    }
}