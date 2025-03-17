<?php

namespace App\Entities\Order;

use App\Entities\Entity;

class OrderItem extends Entity
{
    public $order_id;
    public $good_id;
    public $size;
    public $color;
    public $count = 1;
}