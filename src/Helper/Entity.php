<?php

namespace Sholokhov\Exchange\Helper;

use ReflectionClass;
use ReflectionException;

class Entity
{
    /**
     * Получение атрибута объекта
     * @throws ReflectionException
     */
    public static function getAttribute(string|object $entity, string $attribute): ?object
    {
        $reflection = new ReflectionClass($entity);
        $attr = $reflection->getAttributes($attribute)[0] ?? [];

        if (!$attr) {
            return null;
        }

        return $attr->newInstance();
    }
}