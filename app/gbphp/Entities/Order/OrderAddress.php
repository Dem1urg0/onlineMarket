<?php

namespace App\Entities\Order;
use App\Entities\Entity;

/**
 * Класс сущности Адреса заказа
 */
class OrderAddress extends Entity
{
    /**
     * ID заказа
     * @var int $order_id
     */
    public int $order_id;
    /**
     * ID страны
     * @var int $country_id
     */
    public int $country_id;
    /**
     * Город
     * @var int $city
     */
    public string $city;
    /**
     * Адрес
     * @var int $address
     */
    public string $address;
    /**
     * Почтовый индекс
     * @var int $zip
     */
    public int $zip;
}