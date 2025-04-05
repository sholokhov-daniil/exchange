<?php

namespace Sholokhov\Exchange;

use Sholokhov\Exchange\Fields\FieldInterface;

interface OptionsInterface
{
    /**
     * Карта обмена
     *
     * @return FieldInterface[]
     */
    public function getMap(): array;

    /**
     * Внешний ключ.
     * Используется для идентификации элементов во время импорта.
     *
     * @return string
     */
    public function getForeignKey(): string;
}