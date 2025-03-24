<?php

namespace App\Repositories;

use App\main\App;

/**
 * Класс репозиторий для работы с пользователями
 */
class UserRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'users';
    }

    /**
     * Метод возвращает имя класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->User);
    }

    /**
     * Метод возвращает пользователя по логину
     * @param string $login - логин пользователя
     * @return object
     */
    public function getByLogin($login)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE login = :login";
        return $this->db->queryObject($sql, $this->getEntityClass(), [':login' => $login]);
    }

    /**
     * Метод возвращает всех пользователей
     * @return mixed
     */
    public function getAllUsersInfo()
    {
        $sql = "SELECT users.id, users.login, users.name, users.role, COUNT(orders.id) as count_orders 
        FROM users
        LEFT JOIN orders ON users.id = orders.user_id
        GROUP BY users.id;";
        return $this->db->findAll($sql);
    }

    /**
     * Метод возвращает всех пользователей с фильтром и пагинацией
     * @param array $params - параметры фильтрации и пагинации
     * @param array $data - данные для фильтрации
     * @return mixed
     */
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

    /**
     * Метод возвращает количество пользователей с фильтром
     * @param $data - данные для фильтрации
     * @param $type - тип фильтрации
     * @return false
     */
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