<?php

namespace App\Entities\Order;

use App\Entities\Entity;

/**
 * Класс сущности Заказ
 */
class Order extends Entity
{
    /**
     * ID заказа
     * @var int $id
     */
    public int $id;
    /**
     * Статус заказа
     * @var string $status
     */
    public string $status = 'created';
    /**
     * Дата создания заказа
     * @var string $date
     */
    public $date;
    /**
     * ID пользователя
     * @var int $user_id
     */
    public int $user_id;
}