<?php

namespace Sholokhov\Exchange\Target\Attributes;

use Attribute;

/**
 * Флаг, что метод отвечает за валидацию обмена
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Validate
{
}