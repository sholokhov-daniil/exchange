<?php

namespace Sholokhov\Exchange\Prepares;

use Sholokhov\Exchange\Fields\FieldInterface;

interface PrepareInterface
{
    /**
     * Преобразование значения
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return mixed
     */
    public function prepare(mixed $value, FieldInterface $field): mixed;

    /**
     * Преобразование поддерживает свойство и значение
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return bool
     */
    public function supported(mixed $value, FieldInterface $field): bool;
}