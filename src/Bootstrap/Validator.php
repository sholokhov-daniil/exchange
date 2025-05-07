<?php

namespace Sholokhov\Exchange\Bootstrap;

use ReflectionClass;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Messages\Type\DataResult;
use Sholokhov\Exchange\Target\Attributes\Validate;

/**
 * Производит вызов методов отвечающих за валидацию обмена
 */
class Validator
{
    public function __construct(private readonly object $exchange)
    {
    }

    public function run(): ResultInterface
    {
        $result = new DataResult;

        $chain = array_reverse(class_parents($this->exchange));
        $chain[] = $this->exchange;

        foreach ($chain as $entity) {
            $reflection = new ReflectionClass($entity);
            $methods = $reflection->getMethods();

            foreach ($methods as $method) {
                if ($method->getAttributes(Validate::class)) {
                    $validateResult = $method->invoke($this->exchange);

                    if ($validateResult instanceof DataResult) {
                        $result->addErrors($validateResult->getErrors());
                    }
                }
            }
        }

        return $result;
    }
}