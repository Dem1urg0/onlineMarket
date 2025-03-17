<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

class GoodRepository extends Repository
{
    public function getTableName(): string
    {
        return 'goods';
    }

    public function getEntityClass()
    {
        return get_class(App::call()->Good);
    }

    public function getMaxPrice()
    {
        $sql = "SELECT price FROM goods ORDER BY price DESC LIMIT 1";
        $data = $this->db->find($sql);

        if ($data) {
            return $data['price'];
        } else {
            return 0;
        }
    }

    public function getWithFilter($params, $data)
    {
        $sql = 'SELECT DISTINCT goods.* FROM goods 
                INNER JOIN storage ON goods.id = storage.good_id
                WHERE goods.price >= :min AND goods.price <= :max
                AND storage.count > 0';

        if (!empty($data['category'])) {
            $sql .= ' AND category_id = :category';
        }

        if (!empty($data['gender'])) {
            $sql .= ' AND gender = :gender';
        }

        if (!empty($data['designers'])) {
            $arr = $this->arrayToPDO($data['designers'], 'designer');
            $sql .= ' AND designer_id IN (' . $arr['sql'] . ')';
            unset($data['designers']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($data['brands'])) {
            $arr = $this->arrayToPDO($data['brands'], 'brand');
            $sql .= ' AND brand_id IN (' . $arr['sql'] . ')';
            unset($data['brands']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($data['sizes'])) {
            $arr = $this->arrayToPDO($data['sizes'], 'size');
            $sql .= ' AND size_id IN (' . $arr['sql'] . ')';
            unset($data['sizes']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($params['sort'])) {
            $sql .= ' ORDER BY goods.' . $params['sort'];
        }
        if (!empty($params['count'])) {
            $sql .= " LIMIT " . ($params['page'] - 1) * $params['count'] . ", " . $params['count'];
        }

        return $this->db->findAll($sql, $data);
    }

    public function getCountOfFilter($data)
    {
        $sql = 'SELECT COUNT(DISTINCT goods.id) AS total_filtered FROM goods 
                RIGHT JOIN storage ON goods.id = storage.good_id
                WHERE goods.price >= :min AND goods.price <= :max
                AND storage.count > 0';

        if (!empty($data['category'])) {
            $sql .= ' AND category_id = :category';
        }

        if (!empty($data['gender'])) {
            $sql .= ' AND gender = :gender';
        }

        if (!empty($data['designers'])) {
            $arr = $this->arrayToPDO($data['designers'], 'designer');
            $sql .= ' AND designer_id IN (' . $arr['sql'] . ')';
            unset($data['designers']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($data['brands'])) {
            $arr = $this->arrayToPDO($data['brands'], 'brand');
            $sql .= ' AND brand_id IN (' . $arr['sql'] . ')';
            unset($data['brands']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($data['sizes'])) {
            $arr = $this->arrayToPDO($data['sizes'], 'size');
            $sql .= ' AND size_id IN (' . $arr['sql'] . ')';
            unset($data['sizes']);
            $data = array_merge($data, $arr['data']);
        }

        if (!empty($dbResponse = $this->db->findAll($sql, $data))) {
            return $dbResponse[0]->total_filtered;
        } else {
            return false;
        }
    }
}