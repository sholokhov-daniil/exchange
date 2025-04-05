<?php

namespace Sholokhov\Exchange\Validators;

use TypeError;

use Sholokhov\Exchange\Fields\FieldInterface;
use Sholokhov\Exchange\Messages\Type\Error;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Messages\Type\DataResult;

/**
 * Проверка стандартной карты обмена
 */
class MapValidator implements ValidatorInterface
{
    /**
     * Валидация карты обмена
     *
     * @param mixed $value
     * @return ResultInterface
     */
    public function validate(mixed $value): ResultInterface
    {
        if (!is_array($value)) {
            throw new TypeError("Value must be an array");
        }

        $primary = false;
        $result = new DataResult;

        foreach ($value as $field) {
            if (!($field instanceof FieldInterface)) {
                $result->addError(new Error('Incorrect field description'));
                break;
            }

            if ($field->isKeyField()) {
                if ($primary) {
                    $result->addError(new Error(sprintf('Duplication of the identification field "%s"', $field->getCode())));
                } else {
                    $primary = true;
                }
            }

            if (!$field->getPath()) {
                $result->addError(new Error('Field path is required'));
            }
        }

        if (!$primary) {
            $result->addError(new Error('No identification field'));
        }

        return $result;
    }
}