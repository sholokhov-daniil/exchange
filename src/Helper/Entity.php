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

    /**
     * Получение атрибутов текущего класса с учетом родительских
     *
     * Если у текущего класса отсутствует атрибут, то будет происходить поиск у родительских классов
     *
     * @param string|object $entity
     * @param string $attribute
     * @return object|null
     * @throws ReflectionException
     */
    public static function getAttributeTree(string|object $entity, string $attribute): ?object
    {
        if ($attribute = self::getAttribute($entity, $attribute)) {
            return $attribute;
        }

        foreach (class_parents($entity) as $parent) {
            if ($attribute = self::getAttribute($entity, $attribute)) {
                return $attribute;
            }
        }

        return null;
    }
}