<?php

namespace Sholokhov\Exchange\Helper;

use Bitrix\Main\Diag\Debug;
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
        return self::getAttributeByReflection(new ReflectionClass($entity), $attribute);
    }

    /**
     * Получение атрибута у текущего объекта или его родителя
     *
     * @param string|object $entity
     * @param string $attribute
     * @return object|null
     * @throws ReflectionException
     */
    public static function getAttributeChain(string|object $entity, string $attribute): ?object
    {
        $reflection = new ReflectionClass($entity);
        $fountAttribute = self::getAttributeByReflection($reflection, $attribute);

        if ($fountAttribute) {
            return $fountAttribute;
        }

        while ($reflection->getParentClass()) {
            if ($attribute = self::getAttributeByReflection($reflection->getParentClass(), $attribute)) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * Получение атрибута из описания класса
     *
     * @param ReflectionClass $reflection
     * @param string $attribute
     * @return object|null
     */
    protected static function getAttributeByReflection(ReflectionClass $reflection, string $attribute): ?object
    {
        $attribute = $reflection->getAttributes($attribute)[0] ?? null;
        return $attribute?->newInstance();
    }
}