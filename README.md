# Пользовательский обмен данных


Библиотека позволяет производить обмен данными между различными сущностями оптимизируя трудозатратность и обеспечивая единую структуру.

Эта библиотека рассчитана на расширение со стороны разработчика и позволяет максимальную модификацию процесса без вмешательства в исходный код или копирования существующего функционала с целью доработки под свои требования.

Проект открыт для ваших предложений улучшения совместимости существующих механизмов или разработки новых решений, которые будут крайне полезны, для других разработчиков.


## Установка
Установить последнюю версию библиотеки

````bash
composer require sholokhov/exchange
````

## Базовое использование обмена

````php
use Sholokhov\Exchange\Fields;
use Sholokhov\Exchange\Target\IBlock\Element;

$data = [
    [
        'id' => 56,
        'name' => 'Какой-то элемент',
    ],
    [
        'id' => 15,
        'name' => 'Какой-то элемент 2',
    ]
];

$map = [
    (new Fields\Field)
        ->setPath('id')
        ->setCode('XML_ID')
        ->setKeyField(),
    (new Fields\Field)
        ->setPath('name')
        ->setCode('NAME'),
];

$exchange = new YourExchange;
$exchange->setMap($map);
$result = $exchange->execute($data);
````

## Документация
* [Использование](https://github.com/sholokhov-daniil/exchange/blob/master/doc/01-usage.md)
* [События](https://github.com/sholokhov-daniil/exchange/blob/master/doc/02-events.md)

<br>
<br>

[![Telegram](https://img.shields.io/badge/sholokhov22-50514F?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/sholokhov22)
[![Email](https://img.shields.io/badge/sholokhovdaniil%40yandex.ru-50514F?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAYFBMVEX4YEr4X0n4XEX4VTz4Uzn4WkH5hnj8v7j91tH94N34ZVD+5uP////+8vD/9/b9zsj7uLD+7+393tr//v75cl/4bFn4UDX7ppv5gHH8wrv3TTH8xsD3Rib92dX4WkP4Z1PMr9nAAAAAnklEQVR4AcTPNQLDQBAEwUNhW8z4/1caM2kv9qS1qP4bbax171jJfBQn6TuJuZnJcn55eH0xrR5QlFUM9Q19BY12NmsFdDl0RulewqEFr0P4AB1Cm8BoA2giaCcro3IzMC4yarsCjYxKbzusmYxHBIULjPUFRF5Gt8LqtIhHBA+jdc8ddVbAdKgPPo4Lqqw/s+NTdZ6n8InWr8HpgQQAHnwKoF6Sk9YAAAAASUVORK5CYII=)](mailto:sholokhovdaniil@yandex.ru)
[![VK](https://img.shields.io/badge/daniil.sholokhov-50514F?style=for-the-badge&logo=vk&logoColor=white)](https://vk.ru/daniil.sholokhov)