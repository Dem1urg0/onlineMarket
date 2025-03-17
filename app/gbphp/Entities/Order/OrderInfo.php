<?php

namespace App\Entities\Order;
use App\Entities\Entity;

class OrderInfo extends Entity
{
    public $order_id;
    public $address_id;
    public $billing_id;
    public $shipping;
    public $sale = 0;
}