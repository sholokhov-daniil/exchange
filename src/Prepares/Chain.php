<?php

namespace Sholokhov\Exchange\Prepares;

use Countable;
use Sholokhov\Exchange\Fields\FieldInterface;

/**
 * Цепочка преобразователей данных.
 * Из цепочки выбирается первый подходящий и производится модификация
 */
class Chain implements PrepareInterface, Countable
{
    /**
     * Преобразователи данных
     *
     * @var PrepareInterface[]
     */
    private array $prepares = [];

    /**
     * Преобразование значения
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return mixed
     */
    public function prepare(mixed $value, FieldInterface $field): mixed
    {
        return ($prepared = $this->getSupported($value, $field)) ? $prepared->prepare($value, $field) : $value;
    }

    /**
     * Преобразователь поддерживается
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return bool
     */
    public function supported(mixed $value, FieldInterface $field): bool
    {
        return $this->getSupported($value, $field) !== null;
    }

    /**
     * Количество преобразователей
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->prepares);
    }

    /**
     * Добавление преобразователя
     *
     * @param PrepareInterface $prepare
     * @return PrepareInterface
     */
    public function add(PrepareInterface $prepare): PrepareInterface
    {
        array_unshift($this->prepares, $prepare);
        return $this;
    }

    /**
     * Добавление списка преобразователей
     *
     * @param iterable $iterator
     * @return PrepareInterface
     */
    public function addList(iterable $iterator): PrepareInterface
    {
        foreach ($iterator as $entity) {
            $this->add($entity);
        }

        return $this;
    }

    /**
     * Получение преобразователя, который поддерживает свойство
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return PrepareInterface|null
     */
    private function getSupported(mixed $value, FieldInterface $field): ?PrepareInterface
    {
        foreach ($this->prepares as $prepare) {
            if ($prepare->supported($value, $field)) {
                return $prepare;
            }
        }

        return null;
    }
}