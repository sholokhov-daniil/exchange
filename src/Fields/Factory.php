<?php

namespace Sholokhov\Exchange\Fields;

use Exception;

class Factory
{
    private array $fieldMap = [
        'field' => Field::class,
    ];

    /**
     * Создание свойства на основе массива с настройками
     *
     * @param array $option
     * @return FieldInterface
     * @throws Exception
     */
    public function make(array $option): FieldInterface
    {
        if (!isset($option['entity']) || !array_key_exists($option['entity'], $this->fieldMap)) {
            throw new Exception('Field "' . $option['entity'] . '" not supported');
        }

        $data = is_array($option['data']) ? $option['data'] : [];

        return $this->fieldMap[$option['entity']]::fromArray($data);
    }

    /**
     * Создание массива свойств
     *
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function makeItems(array $options): array
    {
        return array_map(fn(array $option) => $this->make($option), $options);
    }
}