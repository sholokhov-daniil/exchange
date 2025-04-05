<?php

namespace Sholokhov\Exchange;

use Sholokhov\Exchange\Messages\ResultInterface;

use Psr\Log\LoggerAwareInterface;

interface ExchangeInterface extends LoggerAwareInterface
{
    /**
     * Запуск обмена данными
     *
     * @param iterable $source
     * @return ResultInterface
     */
    public function execute(iterable $source): ResultInterface;
}