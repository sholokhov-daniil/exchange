<?php

namespace Sholokhov\Exchange\Target\Attributes;

use Attribute;

/**
 * Отвечает за автоматическую загрузку(конфигурацию обмена)
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class BootstrapConfiguration
{
}