<?php

namespace Sholokhov\Exchange\Normalizers\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Normalizer
{
    public function __construct(
        public readonly string $normalizer,
        public readonly array $options = [],
    )
    {
    }
}