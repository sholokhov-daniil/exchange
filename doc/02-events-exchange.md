# События наследников Exchange

Класс [Exchange](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php)

- [beforeRun](#beforerun)
- [afterRun](#afterrun)
- [beforeAdd](#beforeadd)
- [afterAdd](#afteradd)
- [beforeUpdate](#beforeupdate)
- [afterUpdate](#afterupdate)
- [beforeImportItem](#beforeimportitem)
- [afterImportItem](#afterimportitem)

## beforeRun
Событие вызывается перед запуском импорта и передаются следующие параметры:

| Название |                                         Тип данных                                          | Обязательность |                      Примечание                       |
|:--------:|:-------------------------------------------------------------------------------------------:|:--------------:|:-----------------------------------------------------:|
| exchange | [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php) |       Да       |                           -                           | 

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeRun',
    function(Event $event) {
        /** @var RepositoryInterface $options */
        $options = $event->getParameter('options');
        $options->set('myKey', 'myValue');
    }
);
````

## afterRun
Событие вызывается после импорта данных и передаются следующие параметры:

| Название |                                         Тип данных                                          | Обязательность |                      Примечание                       |
|:--------:|:-------------------------------------------------------------------------------------------:|:--------------:|:-----------------------------------------------------:|
| exchange | [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php) |       Да       |                           -                           |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'afterRun',
    function(Event $event) {
        // ...
    }
);
````

## beforeAdd
Событие вызывается перед добавлением элемента сущности и передаются следующие параметры:

| Название |                                         Тип данных                                          | Обязательность |                      Примечание                      |
|:--------:|:-------------------------------------------------------------------------------------------:|:--------------:|:----------------------------------------------------:|
| exchange | [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php) |       Да       |                          -                           |
|   item   |                                            array                                            |       Да       |            Значение передается по ссылке             |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeAdd',
    function(Event $event) {
        $parameters = &$event->getParameters();
        $parameters['item']['you_field'] = "new_value";
            
        return new EventResult(EventResult::SUCCESS, $parameters);
    }
);
````

> Присутствует возможность отмены добавления значения. Если отменить добавление, то в лог файле появится соответствующее сообщение, но в результате работы импорта это не отобразится.

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeAdd',
    fn(Event $event) => new EventResult(EventResult::ERROR, $event->getParameters());
);
````

## afterAdd
Событие вызывается перед добавлением элемента сущности и передаются следующие параметры:

| Название |                                                  Тип данных                                                  | Обязательность |
|:--------:|:------------------------------------------------------------------------------------------------------------:|:--------------:|
| exchange |         [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php)          |       Да       |
|   item   |                                                    array                                                     |       Да       |
|  result  | [ResultInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/Messages/ResultInterface.php) |       Да       |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'afterAdd',
    function(Event $event) {
        /** @var ResultInterface $result */
        $result = $event->getParameter('result');
        $itemID = $result->getData();
        // ...
    }
);
````

## beforeUpdate
Событие вызывается перед обновлением элемента сущности и передаются следующие параметры:

| Название |                                         Тип данных                                          | Обязательность |                      Примечание                      |
|:--------:|:-------------------------------------------------------------------------------------------:|:--------------:|:----------------------------------------------------:|
| exchange | [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php) |       Да       |                          -                           |
|   item   |                                            array                                            |       Да       |            Значение передается по ссылке             |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeUpdate',
    function(Event $event) {
        $parameters = &$event->getParameters();
        $parameters['item']['you_field'] = "new_value";
        
        return new EventResult(EventResult::SUCCESS, $parameters);
    }
);
````

> Присутствует возможность отмены обновления элемента.

Если отменить добавление, то в лог файле появится соответствующее сообщение, но в результате работы импорта это не отобразится.

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeUpdate',
    function(Event $event) {        
        return new EventResult(EventResult::ERROR, $event->getParameters());
    }
);
````

## afterUpdate
Событие вызывается после обновления элемента сущности и передаются следующие параметры:

| Название |                                                  Тип данных                                                  | Обязательность |
|:--------:|:------------------------------------------------------------------------------------------------------------:|:--------------:|
| exchange |         [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php)          |       Да       |
|   item   |                                                    array                                                     |       Да       |
|  result  | [ResultInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/Messages/ResultInterface.php) |       Да       |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'afterUpdate',
    function(Event $event) {
        /** @var ResultInterface $result */
        $result = $event->getParameter('result');
        $itemID = $result->getData();
        // ...
    }
);
````

## beforeImportItem
Событие вызывается перед импортом элемента сущности, когда еще не определен способ импорта (добавление или обновление) и передаются следующие параметры:

| Название |                                         Тип данных                                          | Обязательность |                      Примечание                      |
|:--------:|:-------------------------------------------------------------------------------------------:|:--------------:|:----------------------------------------------------:|
| exchange | [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php) |       Да       |                          -                           |
|   item   |                                            array                                            |       Да       |            Значение передается по ссылке             |

> В параметре item хранится значение до его нормализации, что позволяет более точно произвести модификацию импортируемого значения

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeImportItem',
    function(Event $event) {
        $parameters = &$event->getParameters();
        $parameters['item']['you_field'] = "new_value";
        
        return new EventResult(EventResult::SUCCESS, $parameters);
    }
);
````

> Присутствует возможность отмены импорта элемента.

Если отменить добавление, то в лог файле появится соответствующее сообщение, но в результате работы импорта это не отобразится.

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'beforeImportItem',
    fn(Event $event) => new EventResult(EventResult::ERROR, $event->getParameters())
);
````

## afterImportItem
Событие вызывается после импорта сущности и передаются следующие параметры:

| Название |                                                  Тип данных                                                  | Обязательность |
|:--------:|:------------------------------------------------------------------------------------------------------------:|:--------------:|
| exchange |         [Текущий объект](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php)          |       Да       |
|   item   |                                                    array                                                     |       Да       |
|  result  | [ResultInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/Messages/ResultInterface.php) |       Да       |

Пример подписки на событие

````php
use Sholokhov\Exchange\Events\Event;
use Sholokhov\Exchange\Events\EventManager;
use Sholokhov\Exchange\Events\EventResult;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Repository\RepositoryInterface;

EventManager::getInstance()->subscribe(
    'afterImportItem',
    function(Event $event) {
        /** @var ResultInterface $result */
        $result = $event->getParameter('result');
        $itemID = $result->getData();
        // ...
    }
);
````