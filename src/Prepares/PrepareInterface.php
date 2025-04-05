<?php

namespace Sholokhov\Exchange\Prepares;

interface PrepareInterface
{
    public function prepare(mixed $value);
}