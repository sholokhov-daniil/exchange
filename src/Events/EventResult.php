<?php

namespace Sholokhov\Exchange\Events;

class EventResult
{
    public const SUCCESS = 0;
    public const FAILURE = 1;
    public const UNDEFINED = -1;

    public function __construct(
        protected string $event,
        protected int $status,
        protected array $parameters = []
    )
    {
    }

    /**
     * Событие, которому принадлежит результат
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * Статус результата события
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Параметры результата
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}