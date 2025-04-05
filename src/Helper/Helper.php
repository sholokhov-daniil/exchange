<?php

namespace Sholokhov\Exchange\Helper;

use Illuminate\Support\Arr;

class Helper
{
    /**
     * Псевдо-идентификатор модуля
     *
     * @return string
     */
    public static function getModuleID(): string
    {
        return 'sholokhov.exchange';
    }

    /**
     * Получение значения по пути из ключей массива
     *
     * @param array $item
     * @param string $path
     * @return mixed
     */
    public static function getArrValueByPath(array $item, $path): mixed
    {
        return Arr::get($item, $path);
    }
}