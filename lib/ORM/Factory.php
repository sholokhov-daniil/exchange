<?php

namespace Sholokhov\Exchange\ORM;

use Exception;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\SystemException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlQueryException;

/**
 * Создание временных таблиц обмена данных
 *
 * Генератор временных таблиц может использоваться вне модуля,
 * тут по возможности будет поддержка обратной совестимости
 */
class Factory
{
    private string $prefixTableName = 'sholokhov_exchange_dynamic_';
    private array $parameters = [];

    private array $fields = [];

    /**
     * Генерациия сущности
     *
     * @return Entity
     * @throws ArgumentException
     * @throws SqlQueryException
     * @throws SystemException
     */
    public function make(): Entity
    {
        $this->parameters['tableName'] = uniqid($this->prefixTableName ?: $this->getDefaultPrefixTableName());
        $entity = Entity::compileEntity($this->parameters['tableName'], $this->fields, $this->parameters);

        $entity->createDbTable();

        if (!$entity->getConnection()->isTableExists($entity->getDBTableName())) {
            throw new Exception(Loc::getMessage('SHOLOKHOV_EXCHANGE_ERROR_CREATE_DYNAMIC_ENTITY'));
        }

        $history = DynamicEntitiesTable::addIfNotExist($entity->getDBTableName());
        if (!$history->isSuccess()) {
            $entity->getConnection()->dropTable($entity->getDBTableName());
            throw new Exception(implode(PHP_EOL, $history->getErrorMessages()));
        }

        return $entity;
    }

    /**
     * Указание префикса наименования таблицы.
     *
     * Если передать пустую строку, то будет подставляться префикс по умолчанию {@see self::getDefaultPrefixTableName}
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefixTableName(string $prefix): Factory
    {
        $this->prefixTableName = $prefix;
        return $this;
    }

    /**
     * Указание параметров конфигурации генератора сущностей
     *
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters): Factory
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Указание полей таблицы
     *
     * @param array $fields Массив должен иметь формат: код_свойства => конфигурация_свойства(свойство)
     * @return $this
     */
    public function setFields(array $fields): Factory
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Префикс таблицы по умолчанию
     *
     * @return string
     */
    private function getDefaultPrefixTableName(): string
    {
        return 'sholokhov_exchange_dynamic_';
    }
}