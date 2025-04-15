<?php

namespace Sholokhov\Exchange\Fields\Normalizer;

use Sholokhov\Exchange\Repository\RepositoryInterface;

class Base
{
    public function normalize(array $data): array
    {
    }

    public function denormalize(RepositoryInterface $repository): array
    {
    }
}