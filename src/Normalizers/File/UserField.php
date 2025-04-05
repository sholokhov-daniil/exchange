<?php

namespace Sholokhov\Exchange\Normalizers\File;

use CFile;
use ReflectionException;

/**
 * Приведение файла в формат, который воспринимают пользовательские свойства (UF)
 */
class UserField extends AbstractNormalizer
{
    /**
     * Приведение к каноничному формату
     *
     * @param mixed $source
     * @return array
     * @throws ReflectionException
     */
    public function normalize(mixed $source): array
    {
        $result = $this->execute($source)->getData();

        if (!is_array($result)) {
            return [];
        }

        return array_map(fn($id) => CFile::MakeFileArray($id), $result);
    }
}