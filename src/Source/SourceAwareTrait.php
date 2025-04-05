<?php

namespace Sholokhov\Exchange\Source;

use Iterator;

/**
 * @implements  SourceAwareInterface
 */
trait SourceAwareTrait
{
    protected ?Iterator $source = null;

    /**
     * Указание источника данных
     *
     * @param Iterator $source
     * @return static
     */
    public function setSource(Iterator $source): static
    {
        $this->source = $source;
        return $this;
    }
}