<?php

namespace App\Entities\Order;
use App\Entities\Entity;

/**
 * Класс сущности данных о заказе
 */
class OrderInfo extends Entity
{
    /**
     * Id заказа
     * @var int $order_id
     */
    public int $order_id;
    /**
     * Id полного адреса
     * @var int $user_id
     */
    public int $address_id;
    /**
     * Id данных пользователя
     * @var int $user_id
     */
    public int $billing_id;
    /**
     * Id данных доставки
     * @var string $shipping
     */
    public string $shipping;
    /**
     * Id скидки
     * @var int $sale
     */
    public int $sale = 0;
}