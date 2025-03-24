<?php

namespace App\Entities\Order;
use App\Entities\Entity;

/**
 * Класс сущности Данных получателя заказа
 */
class OrderBilling extends Entity
{
    /**
     * Id заказа
     * @var int $order_id
     */
    public int $order_id;
    /**
     * Имя
     * @var string $first
     */
    public string $first;
    /**
     * Фамилия
     * @var string $second
     */
    public string $second;
    /**
     * Отчество
     * @var string $sur
     */
    public $sur = NULL;
}