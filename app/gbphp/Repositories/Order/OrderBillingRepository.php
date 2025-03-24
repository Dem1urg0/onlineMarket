<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозиторий данных получателя заказа
 */
class OrderBillingRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'order_billing';
    }
    /**
     * Получение имени класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->OrderBilling);
    }
}