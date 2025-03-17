<?php

namespace App\Repositories;

use App\Entities\User;
use App\main\App;

class UserRepository extends Repository
{
    public function getTableName(): string
    {
        return 'users';
    }

    public function getEntityClass()
    {
        return get_class(App::call()->User);
    }

    public function getByLogin($login)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE login = :login";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':login' => $login]);
    }

    public function getAllUsersInfo()
    {
        $sql = "SELECT users.id, users.login, users.name, users.role, COUNT(orders.id) as count_orders 
        FROM users
        LEFT JOIN orders ON users.id = orders.user_id
        GROUP BY users.id;";
        return $this->db->findAll($sql);
    }

    public function getWithFilter($params, $data)
    {

        $sql = "SELECT users.id, users.login, users.name, users.role, COUNT(orders.id) as count_orders 
                FROM users
                LEFT JOIN orders ON users.id = orders.user_id";

        $sqlOrders = "HAVING count_orders >= :min AND count_orders <= :max";

        $sqlInfo = '';
        if (!empty($params['type'])) {
            $sqlInfo = "WHERE users." . $params['type'] . " LIKE :value";
        }

        $sql .= " $sqlInfo GROUP BY users.id $sqlOrders LIMIT " . ($params['page'] - 1) * $params['count'] . ", " . $params['count'];

        if (!empty($dbData = $this->db->findAll($sql, $data))) {
            return $dbData;
        } else {
            return false;
        }
    }

    public function getCountOfFilter($data, $type)
    {
        $sql = "SELECT COUNT(*) AS total_filtered
                FROM (
                    SELECT users.id
                    FROM users
                    LEFT JOIN orders ON users.id = orders.user_id";

        $sqlInfo = '';
        if (!empty($type)) {
            $sqlInfo = "WHERE users." . $type . " LIKE :value";
        }

        $sqlOrders = "HAVING COUNT(orders.id) >= :min AND COUNT(orders.id) <= :max";

        $sql .= " $sqlInfo GROUP BY users.id $sqlOrders) AS sub;";

        if (!empty($dbData = $this->db->findAll($sql, $data))) {
            return $dbData[0]->total_filtered;
        } else {
            return false;
        }
    }
}