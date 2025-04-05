<?php

namespace Sholokhov\Exchange\Messages\Type;

use Sholokhov\Exchange\Messages\ResultInterface;

/**
 * Результат выполненных действий
 */
class DataResult implements ResultInterface
{
    /**
     * Результат действия
     *
     * @var mixed|null
     */
    protected mixed $data = null;

    /**
     * Ошибки при выполнении действия
     *
     * @var Error[]
     */
    protected array $errors = [];

    /**
     * Работа завершилась успехом
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Установка данных результата работы
     *
     * @param mixed $value
     * @return static
     */
    public function setData(mixed $value): static
    {
        $this->data = $value;
        return $this;
    }

    /**
     * Получить данные результата работы
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Добавить ошибку
     *
     * @param Error $error
     * @return static
     */
    public function addError(Error $error): static
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * Добавить ошибки
     *
     * @param array $errors
     * @return static
     */
    public function addErrors(array $errors): static
    {
        array_walk($errors, [$this, 'addError']);
        return $this;
    }

    /**
     * Установить ошибки
     *
     * @param array $errors
     * @return static
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Получение ошибок
     *
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Получить ошибку по коду
     *
     * @param string $code
     * @return Error|null
     */
    public function getErrorByCode(string $code): ?Error
    {
        foreach ($this->errors as $error) {
            if ($error->getCode() === $code) {
                return $error;
            }
        }

        return null;
    }
}