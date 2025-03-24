<?php

namespace App\Entities\Order;

use App\Entities\Entity;

/**
 * Класс сущности информации о товаре в заказе
 */
class OrderItem extends Entity
{
    /**
     * id заказа
     * @var int $order_id
     */
    public int $order_id;
    /**
     * id товара
     * @var int $good_id
     */
    public int $good_id;
    /**
     * Размер товара
     * @var string $size
     */
    public string $size;
    /**
     * Цвет товара
     * @var string $color
     */
    public string $color;
    /**
     * Количество товара
     * @var int $count
     */
    public int $count = 1;
}