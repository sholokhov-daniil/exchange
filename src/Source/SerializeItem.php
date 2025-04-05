<?php

namespace Sholokhov\Exchange\Source;

use Iterator;
use ArrayIterator;

/**
 * Источник данных на основе сериализованной строки
 *
 * @internal Наследуемся на свой страх и риск
 */
class SerializeItem implements Iterator
{
    private Iterator $iterator;

    /**
     * @param string $data Строка с данными
     * @param bool $multiple Данные являются множественными
     */
    public function __construct(
        private readonly string $data,
        private readonly bool $multiple = true,
    )
    {
    }

    public function fetch(): mixed
    {
        $this->iterator ??= $this->load();
        return $this->iterator->fetch();
    }

    /**
     * Инициализация итератора данных из сериализованной строки
     *
     * @return Iterator
     */
    protected function load(): Iterator
    {
        $data = unserialize($this->data);
        return $this->multiple && is_array($data) ? new ArrayIterator($data) : new ArrayIterator([$data]);
    }

    public function current(): mixed
    {
        if (!isset($this->iterator)) {
            $this->iterator = $this->load();
        }

        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key(): mixed
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }
}