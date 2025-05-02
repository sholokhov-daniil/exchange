# Использование обмена
- [Установка](#установка)
- [Минимальные требования](#минимальные-требования)
- [Описание](#описание)
- [Структура](#структура)
- [Конфигурация](#конфигурация)
  - [Включение деактивации](#включение-деактивации) 
  - [Указание конфигурации](#указание-конфигурации)
- [Карта обмена](#карта-импорта)
  - [Свойства](#доступные-описания-свойств)
    - [Общее](#общее-описание-свойства-импорта)
    - [Информационный блок](#описание-свойства-информационного-блока)
- [Источники данных](#источники-данных)
  - [Json](#источник-данных-Json)
  - [Json file](#источник-данных-json-file)
- [Импорт данных](#импорт-данных)
- [Создание импорта](#создание-импорта)
  - [Application](#application)
  - [Exchange](#abstractExchange)
  - [Пример создания](#пример-создания-класса-импорта)

# Установка
Обмен доступен на Packagist [sholokhov/exchange](https://packagist.org/packages/sholokhov/exchange) и поэтому устанавливается через [Composer](http://getcomposer.org/).

````bash
composer require sholokhov/exchange
````

# Минимальные требования

* Версия php 8.2

# Описание

Модуль обмена позволяет производить импорт\экспорт из любых в любые сущности

# Структура

Все импорты хранятся в директории: **Target**

Все стандартные источники данных хранятся в директории: **Source**

Стандартные нормализаторы данных хранятся в директории: **Normalizers**

Стандартные поля описания карты импорта хранятся в директории: **Fields**

# Конфигурация

Каждый импорт предусматривает указание пользовательской конфигурации, для более детальной настройки.
Конфигурация передается при инициализации импорта в виде массива с произвольным форматом.

Если импорт является наследником класса [Exchange](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php), то поддерживается возможность деактивации значений, которые не приняли участие в обмене.

## Включение деактивации

````php
$config = [
    'deactivate' => true,
];
````

## Указание конфигурации

````php
$config = [
    'client' => $stdClass,
    'deactivate' => false,
];

new Exchange($config)
````

# Карта импорта

Карта импорта представляет собой массив объектов, которые обязаны реализовывать интерфейс [FieldInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/Fields/FieldInterface.php)

## Доступные описания свойств

### Общее описание свойства импорта
Класс [Field](https://github.com/sholokhov-daniil/exchange/blob/master/src/Fields/Field.php)

| Наименование  | Обязательное |                                          Тип данных параметра                                           | Возвращаемый тип данных |                                                                      Описание                                                                       |
|:-------------:|:------------:|:-------------------------------------------------------------------------------------------------------:|:-----------------------:|:---------------------------------------------------------------------------------------------------------------------------------------------------:|
|  setKeyField  |    **Да**    |                                                 boolean                                                 |     Текущий объект      |                     Поле выступает в качестве идентификационного поля(связывает элементы сущности и элементы источника данных)                      |
|    setPath    |    **Да**    |                                                 string                                                  |     Текущий объект      |          Путь до значения, которое вернул источник. Если путь состоит из вложенностей массива, то каждый уровень разделяется символом "."           |
|    setCode    |    **Да**    |                                                 string                                                  |     Текущий объект      |                                       В какой ключ будет записываться значение из структуры данных источника                                        |
|  setMultiple  |     Нет      |                                                 boolean                                                 |     Текущий объект      |                                           Свойство, в которое мы производим импорт является множественным                                           |
|   setTarget   |     Нет      | [ExchangeInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/ExchangeInterface.php) |     Текущий объект      |                                    Вложенный обмен данных - результат обмена будет выступать значением свойства                                     |
| addNormalizer |     Нет      |                  [callable](https://www.php.net/manual/en/language.types.callable.php)                  |     Текущий объект      | Пользовательский нормализатор значения - хорошо подходит, если нам нужно немного обработать значение источника данных (изменить формат даты и т.п.) |

> <font color="#ff0000">Внимание!</font>
> В карте должно быть только одно свойство, которое выступает идентификационным (setKeyField)

## Регистрация карты импорта

````php
use Sholokhov\Exchange\Fields;

$map = [
    (new Field\Field)
         ->setPath('path')
        ->setCode('NEW_KEY')
        ->setMultiple(true)
        ->setTarget($exchange)
        ->setNormalizers(fn($value) => $value + 2)
        ->setKeyField(true),
];

$exchange->setMap($map);
````

# Источники данных
Каждый источник обязан реализовать интерфейс [Traversable](https://www.php.net/manual/ru/class.traversable.php) или быть массивом.

Более подробно описано в документации [iterable](https://www.php.net/manual/ru/language.types.iterable.php)

## Источник данных Json
Класс [Json](https://github.com/sholokhov-daniil/exchange/blob/master/src/Source/Json.php)

Формирует источник данных на основе json строки

Принимаемые значение при инициализации (порядок строк соответствует порядку в конструкторе)

| Описание                                                                              |     Тип данных      | Обязательное | Значение по умолчанию |
|---------------------------------------------------------------------------------------|:-------------------:|:------------:|:---------------------:|
| Json строка с импортируемыми данными                                                  |       Строка        |      Да      |           -           |
| Ключ из которого будут браться данные. Если значение null, то данные берутся из корня | Строка, число, null |     Нет      |         null          |

Если мы производим парсинг данных, представляющие собой массив элементов, которые мы хотим импортировать, то необходимо вызвать метод `setMultiple`

### Пример инициализации множественного значения

````php
$json = json_encode([
    'success' => true,
    'data' => [
        [
            'id' => 356,
            'name' => 'Название элемента 1'
        ],
        //...
    ]
]);

$source = new \Sholokhov\Exchange\Source\Json($json, 'data');
$source->setMultiple();
````

### Пример инициализация единичного значения
````php
$json = json_encode([
    'id' => 356,
    'name' => 'Название элемента 1'
])
$source = new \Sholokhov\Exchange\Source\Json($json);
````

## Источник данных Json file
Класс [JsonFile](https://github.com/sholokhov-daniil/exchange/blob/master/src/Source/JsonFile.php)

Является наследником класса [Json](https://github.com/sholokhov-daniil/exchange/blob/master/src/Source/Json.php)

Формирует источник данных на основе файла хранящий данные в формате json

Поддерживает работу с локальными и удаленными файлами.

Принимаемые значение при инициализации (порядок строк соответствует порядку в конструкторе)

| Описание                                                                              |     Тип данных      | Обязательное | Значение по умолчанию |
|---------------------------------------------------------------------------------------|:-------------------:|:------------:|:---------------------:|
| Путь до файла                                                                         |       Строка        |      Да      |           -           |
| Ключ из которого будут браться данные. Если значение null, то данные берутся из корня | Строка, число, null |     Нет      |         null          |

### Пример инициализации источника данных

````php
$source = new \Sholokhov\Exchange\Source\JsonFile('/var/www/upload/import.json');
````

# Импорт данных

````php
use Sholokhov\Exchange\Source;
use Sholokhov\Exchange\Fields;

$jsonData = json_encode([
    'status' => 'success',
    'data' => [
        [
            'id' => 456,
            'name' => 'Какой-то элемент'
            'author' => 'Иванов'
            'color' => [
                'exterior' => [
                    'id' => 4,
                    'name' => 'Черный'
                ]   
            ],
            'effects' => [
                'effect1',
                'effect2',
                'effect3',
            ], 
        ],
        [
            'id' => 56,
            'name' => 'Какой-то еще элемент'
        ] 
    ]
]);

$map = [
    (new Fields\Field)
        ->setPath('id')
        ->setCode('XML_ID')
        ->setPrimary(),
    (new Fields\Field)
        ->setPath('name')
        ->setCode('CODE'),
    (new Fields\Field)
        ->setPath('name')
        ->setCode('NAME'),
    (new Fields\Field)
        ->setPath('author')
        ->setCode('AUTHOR'),
    (new Fields\Field)
        ->setPath('color.exterior.id')
        ->setCode('COLOR')
        ->setTarget(
            (new Element)
                ->setMap(
                    [
                        (new Fields\Field)
                            ->setPath('color.exterior.id')
                            ->setCode('XML_ID')
                            ->setPrimary(),
                        (new Fields\Field)
                            ->setPath('color.exterior.name')
                            ->setCode('NAME')
                    ]           
                )
        ),
    (new Fields\Field)
        ->setPath('effects')
        ->setCode('EFFECTS')
        ->setMultiple(true),        
];

$source = new Source\Json($jsonData, 'data');
$exchange = new MyExchange;
$exchange->setMap($map);
$result = $exchange->execute($source);
````

# Создание импорта

Каждый импорт обязан реализовать интерфейс [ExchangeInterface](https://github.com/sholokhov-daniil/exchange/blob/master/src/ExchangeInterface.php)

Существует 2 абстрактных класса, которые описывают обмен данными:

## Application
Класс [Application](https://github.com/sholokhov-daniil/exchange/blob/master/src/Application.php)

Основной класс обмена данных.

Цель объекта:
* Инициализирует объекта хранения конфигураций обмена
* Инициализация объекта кэширования обмена
* Обработка пользовательской конфигурации обмена

Поддерживает атрибуты наследников класса:
* [OptionsContainer](https://github.com/sholokhov-daniil/exchange/blob/master/src/Target/Attributes/OptionsContainer.php) - Указывается сущность хранения конфигураций
* [CacheContainer](https://github.com/sholokhov-daniil/exchange/blob/master/src/Target/Attributes/CacheContainer.php) - Указывается сущность хранения кэша обмена

## Exchange
Класс [Exchange](https://github.com/sholokhov-daniil/exchange/blob/master/src/Exchange.php)

Обмен данных регламентирующий структуру обмена и производящий обработку данных

Цель объекта:
* Задает порядок выполнения обмена
* Получение данных из источника
* Нормализация данных источника данных на основе карты импорта
* Валидация карты импорта
* Запуск "подимпорта" на основе карты
* Поиск существования элемента сущности на основе данных источника
* Добавление в сущность
* Обновление элемента сущности
* Деактивация элементов сущности, которые не пришли в обмене

Поддерживает атрибуты наследников класса:
* [MapValidator](https://github.com/sholokhov-daniil/exchange/blob/master/src/Target/Attributes/MapValidator.php) - Валидатор карты обмена


## Пример создания класса импорта

````php
use Sholokhov\Exchange\Exchange;
use Sholokhov\Exchange\Messages\ResultInterface;
use Sholokhov\Exchange\Messages\Type\Error;
use Sholokhov\Exchange\Messages\Type\DataResult;
use Sholokhov\Exchange\Target\Attributes\Validate;
use Sholokhov\Exchange\Target\Attributes\MapValidator;
use Sholokhov\Exchange\Target\Attributes\CacheContainer;
use Sholokhov\Exchange\Target\Attributes\OptionsContainer;
use Sholokhov\Exchange\Target\Attributes\BootstrapConfiguration;

#[MapValidator('custom map validation')]
#[OptionsContainer('custom options registry')]
#[CacheContainer('custom cache container')]
class Queue extends Exchange
{
    // Обработка параметров обмена
    protected function normalizeOptions(array $options): array
    {
        if (!isset($options['chanel'])) {
            $options['chanel'] = 'default';
        }
        
        return $options;
    }

    // Производит валидацию перед запуском обмена
    #[Validate]
    private function validateOptions(): ResultInterface
    {
        $result = new DataResult;
        
        if (!$this->getOptions()->get('TEST_KEY')) {
            $result->addError(new Error('TEST_KEY not found'))
        }
    }

    // Конфигурация обмена после инициализации конструктора
    #[BootstrapConfiguration]
    private function configure(): void
    {
        $this->event->subscribeAfterRun([$this, 'checkCount']);
    }

    // Проверка существования элемента в очереди
    protected function exists(array $item): bool
    {
        // Получение свойство, которое отвечает за связь элементов сущности и элементов источника
        $keyField = $this->getPrimaryField();
        $keyValue = $item[$keyField->getCode()];
        
        if ($this->cache->has($keyValue)) {
            return $this->cache->get($keyValue);
        }
        
        $entity = new DataManager;
        $row = $entity::getRow([
            'filter' => [
                $keyField->getCode() => $keyValue,
                'CHANEL' => $this->getOptions()->get('chanel')
            ],
            'select' => ['ID']
        ]);
        
        if ($row) {
            $this->cache->set($keyValue, (int)$row['ID']);
            return true;
        }
        
        return false;
    }
    
    // Добавление в очередь
    protected function add(array $item): ResultInterface
    {
        // ...
    }
    
    // Обновление элемента очереди
    protected function update(array $item): ResultInterface
    {
        $keyField = $this->getPrimaryField();
        $keyValue = $item[$keyField->getCode()];
        $id = $this->cache->get($keyValue);
       
        
        if (!$id) {
            return $this->add($item);
        }
        
        DataManager::add($item);
        
        // ...
    }
    
    // Деактивация старых элементов очереди
    protected function deactivate(): void
    {
        $iterator = DataManager::getList(
            [
                'filter' => [
                    '<DATE_UPDATE' => DateTime::createFromTimestamp($this->timeUp)
                ]
            ]       
        );
        
        // ...
    }
    
    // Действие после обмена
    private function checkCount(): void 
    {
        // ...
    }
}
````