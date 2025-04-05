<?php

namespace Sholokhov\Exchange\Target\Attributes;

use Attribute;

use Sholokhov\Exchange\Validators\MapValidator as Validator;

#[Attribute(Attribute::TARGET_CLASS)]
class MapValidator
{
    public function __construct(private readonly string $entity = Validator::class)
    {
    }

    public function getEntity(): string
    {
        return $this->entity;
    }
}