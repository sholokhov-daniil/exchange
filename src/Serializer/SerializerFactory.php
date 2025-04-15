<?php

namespace Sholokhov\Exchange\Serializer;

use ReflectionClass;

use Sholokhov\Exchange\Helper\Entity;
use Sholokhov\Exchange\Normalizers\Attributes\Normalizer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class SerializerFactory
{
    /**
     * Создание сериализатора
     *
     * @param object|string $entity
     * @return Serializer
     * @throws \ReflectionException
     */
    public function makeByEntity(object|string $entity): Serializer
    {
        $normalizer = $this->getAttribute($entity, Normalizer::class);

        if (!($normalizer instanceof NormalizerInterface)) {
            throw new \Exception('Normalizer must implement ' . NormalizerInterface::class);
        }

        $encoders = $this->getAttribute($entity, Normalizer::class);

        if (!($encoders instanceof EncoderInterface)) {
            throw new \Exception('Encoder must implement ' . EncoderInterface::class);
        }

        return new Serializer([$normalizer], [$encoders]);
    }

    /**
     * Получение атрибута класса
     *
     * @param object|string $entity
     * @param string $attribute
     * @return object|null
     * @throws \ReflectionException
     */
    private function getAttribute(object|string $entity, string $attribute): ?object
    {
        $attribute = Entity::getAttributeTree($entity, $attribute);

        if (!$attribute) {
            return null;
        }

        $reflection = new ReflectionClass($attribute->entity);
        return $reflection->newInstance($attribute->options);
    }
}