<?php

namespace Sholokhov\Exchange\Normalizers;

use ReflectionClass;
use ReflectionException;
use Sholokhov\Exchange\Helper\Entity;
use Sholokhov\Exchange\Normalizers\Attributes\Normalizer;

class Factory
{
    /**
     * Создание нормализатора
     *
     * @param object|string $field
     * @return object|null
     * @throws ReflectionException
     */
    public function make(object|string $field): ?object
    {
        /** @var Normalizer|null $attribute */
        $attribute = Entity::getAttributeTree($field, Normalizer::class);

        if (!$attribute) {
            return null;
        }

        $reflection = new ReflectionClass($attribute->normalizer);
        return $reflection->newInstance($attribute->options);
    }
}