<?php

namespace Sholokhov\Exchange\Repository;

use Countable;
use Iterator;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * Представление контейнера.
 * Контейнер производит зранение различных значений.
 * С контейнером можно работать как с массивом (частично).
 *
 * Класс реализует паттерн "контейнер"
 * @link https://ru.wikipedia.org/wiki/%D0%9A%D0%BE%D0%BD%D1%82%D0%B5%D0%B9%D0%BD%D0%B5%D1%80_%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 *
 * @extends Iterator
 * @extends Countable
 * @extends PsrContainer
 */
interface RepositoryInterface extends Iterator, Countable, PsrContainer
{
    /**
     * Получить значение
     *
     * @param string $id
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $id, mixed $default = null): mixed;

    /**
     * Указать значение
     *
     * @param string $id
     * @param mixed $value
     * @return void
     */
    public function set(string $id, mixed $value): void;

    /**
     * Проверка наличия свойства.
     *
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool;
}