<?php

namespace Sholokhov\Exchange\Normalizers;

use DateTime;
use DateTimeZone;

/**
 * Нормализация данных, связанных с датой и время
 */
class DateNormalizer
{
    /**
     * Приведение произвольного значения даты в стандартный объект даты и время
     *
     * @param array|string|int $date
     * @param DateTimeZone|null $timeZone
     * @return DateTime|array
     * @throws \DateMalformedStringException
     */
    public static function createDateTime(array|string|int $date, DateTimeZone $timeZone = null): DateTime|array
    {
        return is_array($date) ? array_map(fn($value) => new DateTime($value, $timeZone), $date) : new DateTime($date, $timeZone);
    }
}