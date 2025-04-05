<?php

namespace Sholokhov\Exchange\Events;

class EventManager
{
    private static self $instance;
    private array $handlers = [];

    private function __construct()
    {
    }

    /**
     * Отправка события
     *
     * @param Event $event
     * @return EventResult[]
     */
    public function send(Event $event): array
    {
        $result = [];
        $handlers = $this->handlers[$event->getId()] ?? [];

        foreach ($handlers as $subscribe) {
            $eventResult = call_user_func($subscribe->getCallback(), $event);
            if ($eventResult instanceof EventResult) {
                $result[] = $eventResult;
            }
        }

        return $result;
    }

    /**
     * Подписка на событие
     *
     * @param string $event
     * @param callable $callback
     * @param int $sort
     * @return void
     */
    public function subscribe(string $event, callable $callback, int $sort = 500): void
    {
        $subscribe = new Subscribe($event, $callback, $sort);

        if (!is_array($this->handlers[$event])) {
            $this->handlers[$event] = [];
        }

        $this->handlers[$event][] = $subscribe;

        usort($this->handlers[$event], fn(Subscribe $a, Subscribe $b) => $a->getSort() <=> $b->getSort());
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self;
    }
}