<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозиторий заказов
 */
class OrderRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'orders';
    }

    /**
     * Получение имени класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->Order);
    }

    /**
     * Получение заказов по id пользователя
     * @param $user_id - id пользователя
     * @return mixed
     */
    public function getOrdersByUserId($user_id)
    {
        $sql = "SELECT orders.id, name, price, order_list.size, order_list.color, count 
                FROM orders 
                INNER JOIN order_list ON orders.id = order_list.order_id 
                INNER JOIN goods ON goods.id = order_list.good_id 
                      WHERE user_id = :user_id";

        return $this->db->findAll($sql, [':user_id' => $user_id]);
    }

    /**
     * Получение информации о заказе по его id
     * @param $order_id - id заказа
     * @return mixed
     */
    public function getOrderInfo($order_id)
    {
        $sql = "SELECT 
            orders.user_id,
            countries.country,
            order_address.city,
            order_address.address,
            order_address.zip,
            order_billing.first,
            order_billing.second,
            order_billing.sur,
            order_info.shipping,
            order_info.sale,
            orders.date,
            orders.status
        FROM 
            `order_info`
        INNER JOIN 
            `orders` ON orders.id = order_info.order_id
        INNER JOIN 
            `order_address` ON order_info.address_id = order_address.id
        INNER JOIN 
            `order_billing` ON order_billing.id = order_info.billing_id
        INNER JOIN 
            `countries` ON order_address.country_id = countries.id
        WHERE 
            order_info.order_id = :order_id";
        return $this->db->find($sql, [':order_id' => $order_id]);
    }

    /**
     * Получение всех заказов
     * @return mixed
     */
    public function getAllOrders()
    {
        $sql = "SELECT orders.id, name, price, order_list.size, order_list.color, count 
                FROM orders 
                INNER JOIN order_list ON orders.id = order_list.order_id 
                INNER JOIN goods ON goods.id = order_list.good_id";
        return $this->db->findAll($sql);
    }

    /**
     * Удаление заказа по его id
     * @param $order_id - id заказа
     * @return mixed
     */
    public function deleteOrder($order_id)
    {
        $sql = "DELETE `orders`, `order_list`, `order_billing`, `order_address`, `order_info`
        FROM `orders`
        INNER JOIN `order_list` ON order_list.order_id = orders.id
        INNER JOIN `order_billing` ON order_billing.order_id = orders.id
        INNER JOIN `order_address` ON order_address.order_id = orders.id
        INNER JOIN `order_info` ON order_info.order_id = orders.id
        WHERE orders.id = :order_id";
        return $this->db->exec($sql, [':order_id' => $order_id]);
    }

    /**
     * Изменение статуса заказа
     * @param $order_id - id заказа
     * @param $status - статус заказа
     * @return mixed
     */
    public function changeOrderStatus($order_id, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
        return $this->db->exec($sql, [':order_id' => $order_id, ':status' => $status]);
    }

    /**
     * Получение id пользователя по id заказа
     * @param $order_id - id заказа
     * @return mixed
     */
    public function getOwnerOrder($order_id)
    {
        $sql = "SELECT user_id FROM orders WHERE id = :order_id";
        $table = $this->db->find($sql, [':order_id' => $order_id]);
        return $table['user_id'];
    }

    /**
     * Получение количества заказов по id пользователя
     * @param $user_id - id пользователя
     * @return mixed
     */
    public function getOrdersCountByUserId($user_id)
    {
        $sql = "SELECT COUNT(id) as count FROM orders WHERE user_id = :user_id";
        return $this->db->find($sql, [':user_id' => $user_id]);
    }

    /**
     * Получение максимального количества заказов среди всех пользователей
     * @return mixed
     */
    public function getMaxOrdersCount()
    {
        $sql = "SELECT COUNT(id) as count FROM orders
        GROUP BY user_id
        ORDER BY count DESC
        LIMIT 1";
        $data = $this->db->find($sql);

        if ($data) {
            return $data['count'];
        }
        return '';
    }
}