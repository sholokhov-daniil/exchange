<?php

namespace Sholokhov\Exchange\Source;

interface SourceItemInterface
{
    /**
     * Код значения.
     * Используется при маршрутизации
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Значение свойства
     *
     * @return mixed
     * @author Daniil S.
     */
    public function getValue(): mixed;
}