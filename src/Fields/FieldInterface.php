<?php

namespace Sholokhov\Exchange\Fields;

use Sholokhov\Exchange\ExchangeInterface;

/**
 * Описание настроек свойства
 */
interface FieldInterface
{
    /**
     * Поле отвечает за идентификацию значений.
     * На основе данного поля происходит определение наличия импортированного значения
     * или обновление существующего.
     *
     * @return bool
     */
    public function isPrimary(): bool;

    /**
     * Получение пути хранения значения
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Код свойства в которое необходимо записать значение
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Цель значения
     *
     * @return ?ExchangeInterface
     */
    public function getTarget(): ?ExchangeInterface;

    /**
     * Значение является множественным
     *
     * @return bool
     */
    public function isMultiple(): bool;

    /**
     * Получение дочернего элемента
     *
     * @return FieldInterface|null
     */
    public function getChildren(): ?FieldInterface;

    /**
     * Получение валидаторов значения свойства
     *
     * @return callable[]
     */
    public function getNormalizers(): array;
}