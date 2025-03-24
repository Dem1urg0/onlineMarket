<?php

namespace App\Repositories;

use App\Entities\Entity;
use App\main\App;
use App\services\db;

/**
 * Основной абстрактный класс репозитория
 */
abstract class Repository
{
    /**
     * Экземпляр класса для работы с БД
     * @var db
     */
    protected db $db;

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->db = App::call()->db;
    }

    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName()
    {
    }

    /**
     * Получение имени сущности
     * @return string
     */
    public function getEntityClass()
    {
    }

    /**
     * Получение одной записи по id
     * @param int $id
     * @return object
     */
    public function getOne($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':id' => $id]);
    }

    /**
     * Получение всех записей
     * @return array
     */
    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return $this->db->queryObjects($sql, $this->getEntityClass());
    }

    /**
     * Получение количества всех записей
     * @return int
     */
    public function getCountOfAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) AS total FROM {$tableName}";

        if (!empty($count = App::call()->db->findAll($sql)[0]->total)) {
            return $count;
        } else {
            return false;
        }
    }

    /**
     * Получение записи по имени
     * @param $name
     * @return mixed
     */
    public function getByName($name)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE name = :name";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':name' => $name]);
    }

    /**
     * Удаление записи
     * @param Entity $entity - сущность
     */
    public function delete(Entity $entity)
    {
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id = :id";
        $this->db->exec($sql, [':id' => $entity->id]);
    }

    /**
     * Маршрутизатор сохранения записи.
     * Сохраняет или изменяет запись в зависимости от наличия id
     * @param Entity $entity - сущность
     * @return int
     */
    public function save(Entity $entity)
    {
        if (!empty($entity->id)) {
            if ($this->getOne($entity->id)) {
                $this->update($entity);
            } else {
                return $this->insert($entity);
            }
        } else {
            return $this->insert($entity);
        }
        return $entity->id;
    }

    /**
     * Преобразование массива для PDO.
     * Например, массив брендов преобразуется в строку 'brand1, brand2, ...'
     * @param array $arr - массив для преобразования
     * @param string $name - имя (например, 'brand', выйдет brand1, brand2, ...)
     * @return array
     */
    public function arrayToPDO($arr, $name)
    {
        $string = '';
        $data = [];
        foreach($arr as $key => $value){
            $data[$name . $key] = $value;
            $string .= ":$name{$key}";
            if (count($arr) !== $key + 1){
                $string .= ', ';
            }
        }
        return [
            'sql' => $string,
            'data' => $data
        ];
    }

    /**
     * Вставка записи
     * @param Entity $entity - сущность
     * @return int
     */
    private function insert(Entity $entity): int
    {
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} ({$this->getParams('names', $entity)}) VALUES ({$this->getParams('values', $entity)})";

        // Выполняем запрос
        $this->db->exec($sql, $this->getParams('params', $entity));

        return (int)$this->db->lastInsertId();
    }

    /**
     * Обновление записи
     * @param Entity $entity - сущность
     * @return bool
     */
    private function update(Entity $entity)
    {
        if (!$this->getParams('equality', $entity, ['id'])) {
            return 'Не все параметры заполнены';
        }

        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET {$this->getParams('equality',$entity, ['id'])} WHERE id = :id";
        $this->db->exec($sql, $this->getParams('params', $entity));
        return true;
    }

    /**
     * Получение параметров для запроса.
     * В зависимости от переданного параметра формирует строку с именами, значениями или параметрами
     * @param string $name - тип параметра
     * @param Entity $entity - сущность
     * @param array $filter - массив параметров, которые необходимо исключить
     * @return mixed
     */
    private function getParams($name, Entity $entity, $filter = [])
    {
        $paramsReq = [];
        foreach ($entity as $param => $value) {
            if (in_array($param, $filter)) {
                continue;
            }
            if ($param == 'date' && empty($value)) {
                $value = date('Y-m-d H:i:s');
            }
            switch ($name) {
                case 'names':
                    $paramsReq[] = $param;
                    break;
                case 'values':
                    $paramsReq[] = ':' . $param;
                    break;
                case 'params':
                    $paramsReq[':' . $param] = $value;
                    break;
                case 'equality':
                    $paramsReq[] = $param . ' = :' . $param;
                    break;
            }
        }
        return match ($name) {
            'names', 'values', 'equality' => implode(', ', $paramsReq),
            'params' => $paramsReq,
            default => null,
        };
    }
}
