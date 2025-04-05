<?php

namespace Sholokhov\Exchange\Messages\Type;

use Throwable;

class Error
{
    public function __construct(
        protected string $message,
        protected int $code = 0,
        protected array $context = []
    )
    {
    }

    public function __toString(): string
    {
        return $this->message;
    }

    /**
     * Создание ошибки на основе исключения
     *
     * @param Throwable $throwable
     * @param array $context
     * @return static
     */
    public static function createFromThrowable(Throwable $throwable, array $context = [])
    {
        return new static($throwable->getMessage(), $throwable->getCode(), $context);
    }

    /**
     * Получение текста ошибки
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Получение кода ошибки
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Получение контекста ошибки
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}