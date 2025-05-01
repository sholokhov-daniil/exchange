<?php

namespace Sholokhov\Exchange\Messages;

use Sholokhov\Exchange\Messages\Type\Error;

interface ResultInterface
{
    /**
     * Успешный результат (отсутствие ошибок)
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Указание результата работы
     *
     * @param mixed $value
     * @return $this
     */
    public function setData(mixed $value): self;

    /**
     * Получение результата работы
     *
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * Добавление ошибки
     *
     * @param Error $error
     * @return $this
     */
    public function addError(Error $error): self;

    /**
     * Добавление ошибок
     *
     * @param array $errors
     * @return $this
     */
    public function addErrors(array $errors): self;

    /**
     * Указание нового списка ошибок (старые будут удалены)
     *
     * @param Error[] $errors
     * @return $this
     */
    public function setErrors(array $errors): self;

    /**
     * Получение всех ошибок
     *
     * @return Error[]
     */
    public function getErrors(): array;

    /**
     * Получение ошибочных сообщений
     *
     * @return string[]
     */
    public function getErrorMessages(): array;

    /**
     * Получение ошибки по коду
     *
     * @param string $code
     * @return Error|null
     */
    public function getErrorByCode(string $code): ?Error;
}