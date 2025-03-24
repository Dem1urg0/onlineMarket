<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозитория адресов заказов
 */
class OrderAddressRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'order_address';
    }
    /**
     * Метод возвращает имя сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->OrderAddress);
    }
}