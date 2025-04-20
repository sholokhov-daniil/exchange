<?php

namespace Sholokhov\Exchange\Helper;

use Sholokhov\Exchange\Fields\FieldInterface;

class FieldHelper
{
    /**
     * Приведение формата значения согласно его описанию
     *
     * @param mixed $value
     * @param FieldInterface $field
     * @return mixed
     */
    public static function normalizeValue(mixed $value, FieldInterface $field): mixed
    {
        if ($field->isMultiple() && !is_array($value)) {
            return $value === null ? [] : [$value];
        } elseif (!$field->isMultiple() && is_array($value)) {
            return reset($value);
        }

        return $value;
    }

    /**
     * Получение значения свойства
     *
     * @param array $item
     * @param FieldInterface $field
     * @return mixed
     */
    public static function getValue(array $item, FieldInterface $field): mixed
    {
        return $field->getChildren() ? self::getTreeValue($item, $field) : Helper::getArrValueByPath($item, $field->getPath());
    }

    /**
     * Получение значение свойства ветвления
     *
     * @param array $item
     * @param FieldInterface $field
     * @return array|null
     */
    public static function getTreeValue(array $item, FieldInterface $field): ?array
    {
        $result = [];

        $root = Helper::getArrValueByPath($item, $field->getPath());

        if (!is_array($root)) {
            return null;
        }

        $childrenField = $field->getChildren();

        if (!array_is_list($root)) {
            $root = [$root];
        }

        foreach ($root as $children) {
            if ($childrenField->getChildren()) {
                $result = array_merge($result, self::getTreeValue($children, $childrenField));
            } elseif (is_array($children)) {
                $result[] = self::getValue($children, $childrenField);
            }
        }

        return $result;
    }
}