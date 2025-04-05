<?php

namespace Sholokhov\Exchange\Events;

class Event
{
    public function __construct(
        protected string $id,
        protected array $parameters = []
    )
    {
    }

    /**
     * Вызов события
     *
     * Результатом функции являются ответы обработчиков
     *
     * @return EventResult[]
     */
    public function send(): array
    {
        return EventManager::getInstance()->send($this);
    }

    /**
     * Идентификатор события
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Параметры события
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}