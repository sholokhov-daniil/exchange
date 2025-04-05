<?php

$_SERVER['argv'] = [
    __DIR__ . '../../vendor/phpunit/phpunit/phpunit',
    '--configuration',
    __DIR__ . '/phpunit.xml',
    __DIR__
];

@require __DIR__ . '/../vendor/phpunit/phpunit/phpunit';