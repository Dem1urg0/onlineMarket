<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозиторий информации о заказе
 */
class OrderInfoRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'order_info';
    }
    /**
     * Получение имени класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        get_class(App::call()->OrderInfo);
    }
}