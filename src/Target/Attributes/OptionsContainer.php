<?php

namespace Sholokhov\Exchange\Target\Attributes;

use Attribute;
use Sholokhov\Exchange\Repository\Types\Memory;

#[Attribute(Attribute::TARGET_CLASS)]
class OptionsContainer
{
    public function __construct(private readonly string $entity = Memory::class)
    {
    }

    public function getEntity(): string
    {
        return $this->entity;
    }
}