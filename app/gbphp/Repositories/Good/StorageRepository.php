<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозитория хранилища товаров
 */
class StorageRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName()
    {
        return 'storage';
    }

    /**
     * Метод возвращает имя класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->Storage);
    }

    /**
     * Метод возвращает информацию о товаре из хранилища по его id
     * @param $good_id - id товара
     * @return mixed
     */
    public function getInfoFromStorage($good_id)
    {
        $sql = "SELECT colors.name AS color, sizes.name AS size
        FROM `storage`
        INNER JOIN colors ON storage.color_id = colors.id
        INNER JOIN sizes ON storage.size_id = sizes.id
        WHERE storage.count > 0 AND storage.good_id = :good_id";
        return $this->db->queryObjects($sql, $this->getEntityClass(), [':good_id' => $good_id]);
    }
}