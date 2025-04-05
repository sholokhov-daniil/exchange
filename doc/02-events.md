# События обмена
- [Базовый класс обмена (Sholokhov\Exchange\Exchange)](https://github.com/sholokhov-daniil/exchange/blob/master/doc/02-events-exchange.md)

Все события регистрируются через класс [EventManager](https://github.com/sholokhov-daniil/exchange/blob/master/src/Events/EventManager.php)


Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;

EventManager::getInstance()->subscribe(
    'eventName', 
    function(Event $event) {
        // ....
    },
    $sort
        
);
````
