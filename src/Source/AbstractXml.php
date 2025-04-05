<?php

namespace Sholokhov\Exchange\Source;

use Iterator;
use EmptyIterator;
use Sholokhov\Exchange\Helper\SourceHelper;

/**
 * Базовое представление xml источников данных
 *
 * @internal
 */
abstract class AbstractXml implements Iterator
{
    use IterableTrait;

    /**
     * Родительский тег элементов
     *
     * @var string
     */
    protected string $rootTag = 'data';

    /**
     * @param string $path Путь до xml файла
     */
    public function __construct(protected readonly string $path)
    {
    }

    /**
     * Парсинг xml файла
     *
     * @param mixed $resource
     * @return Iterator
     */
    abstract protected function parsing(mixed $resource): Iterator;

    /**
     * Указание родительского тега элементов
     *
     * Если изменение происходит после формирования указателя({@see self::fetch()}), то он сбрасывается
     *
     * @param string $rootTag
     * @return $this
     */
    public function setRootTag(string $rootTag): self
    {
        $this->rootTag = $rootTag;

        if ($this->iterator) {
            $this->iterator = null;
        }

        return $this;
    }

    /**
     * Загрузка данных источника
     *
     * @return Iterator
     */
    final protected function load(): Iterator
    {
        $resource = SourceHelper::download($this->path);

        if (!$resource) {
            return new EmptyIterator();
        }

        return $this->parsing($resource);
    }
}