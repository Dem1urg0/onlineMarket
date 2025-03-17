<?php

namespace App\Repositories;

use App\Entities\Entity;
use App\main\App;

abstract class Repository
{
    protected $db;

    public function __construct()
    {
        $this->db = App::call()->db;
    }

    public function getTableName()
    {
    }

    public function getEntityClass()
    {
    }

    public function getOne($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':id' => $id]);
    }

    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return $this->db->queryObjects($sql, $this->getEntityClass());
    }

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

    public function getByName($name)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE name = :name";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':name' => $name]);
    }

    public function delete(Entity $entity)
    {
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id = :id";
        $this->db->exec($sql, [':id' => $entity->id]);
    }

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
    }

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

    private function insert(Entity $entity): int
    {
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} ({$this->getParams('names', $entity)}) VALUES ({$this->getParams('values', $entity)})";

        // Выполняем запрос
        $this->db->exec($sql, $this->getParams('params', $entity));

        return (int)$this->db->lastInsertId();
    }

    private function update(Entity $entity)
    {
        if (!$this->getParams('equality', $entity, ['id'])) {
            return 'Не все параметры заполнены';
        }

        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET {$this->getParams('equality',$entity, ['id'])} WHERE id = :id";
        $this->db->exec($sql, $this->getParams('params', $entity));
    }

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
        switch ($name) {
            case 'names':
            case 'values':
            case 'equality':
                return implode(', ', $paramsReq);
            case 'params':
                return $paramsReq;
            default:
                return null;
        }
    }
}
