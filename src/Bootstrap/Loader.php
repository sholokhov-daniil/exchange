<?php

namespace Sholokhov\Exchange\Bootstrap;

use ReflectionClass;
use ReflectionException;
use Sholokhov\Exchange\Target\Attributes\BootstrapConfiguration;

/**
 * Производит вызов всех методов отвечающий за загрузку конфигураций обмена
 */
class Loader
{
    public function __construct(private readonly object $exchange)
    {
    }

    /**
     * Выполнить загрузку
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $chain = array_reverse(class_parents($this->exchange));
        $chain[] = $this->exchange;
        array_walk($chain, [$this, 'run']);
    }

    /**
     * Автозагрузка конфигураций объекта
     *
     * @param string|object $entity
     * @return void
     * @throws ReflectionException
     */
    private function run(string|object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $methods = $reflection->getMethods();

        foreach ($methods as $method) {
            if ($method->getAttributes(BootstrapConfiguration::class)) {
                $method->invoke($this->exchange);
            }
        }
    }
}