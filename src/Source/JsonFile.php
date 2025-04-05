<?php

namespace Sholokhov\Exchange\Source;

use Sholokhov\Exchange\Helper\IO;

/**
 * Источник данных на основе json файла
 *
 * @internal Наследуемся на свой страх и риск
 */
class JsonFile extends Json
{
    /**
     * @param string $path Место размещения json файла (локально или удаленно)
     * @param string $sourceKey Ключ из которого необходимо брать данные. Если не указать, что подгружаются все данные
     */
    public function __construct(string $path, string $sourceKey = '')
    {
        parent::__construct(IO::getFileContent($path), $sourceKey);
    }
}