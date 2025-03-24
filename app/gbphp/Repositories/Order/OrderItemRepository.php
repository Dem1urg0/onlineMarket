<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозиторий товаров в заказе
 */
class OrderItemRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName()
    {
        return 'order_list';
    }
    /**
     * Получение имени класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->OrderItem);
    }
}