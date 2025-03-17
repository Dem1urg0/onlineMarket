<?php

namespace App\Entities\Order;
use App\Entities\Entity;

class OrderBilling extends Entity
{
    public $order_id;
    public $first;
    public $second;
    public $sur = NULL;
}