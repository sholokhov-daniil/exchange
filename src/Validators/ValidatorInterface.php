<?php

namespace Sholokhov\Exchange\Validators;

use Sholokhov\Exchange\Messages\ResultInterface;

/**
 * Производит валидацию передаваемого значения
 */
interface ValidatorInterface
{
    /**
     * Валидация значения
     *
     * @param mixed $value
     * @return ResultInterface
     */
    public function validate(mixed $value): ResultInterface;
}