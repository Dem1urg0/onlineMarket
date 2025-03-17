<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

class StorageRepository extends Repository
{
    public function getTableName()
    {
        return 'storage';
    }

    public function getEntityClass()
    {
        return get_class(App::call()->Storage);
    }
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