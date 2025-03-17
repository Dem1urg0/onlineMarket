<?php

namespace App\Entities\Order;

use App\Entities\Entity;

class Order extends Entity
{
    public $id;
    public $status = 'created';
    public $date;
    public $user_id;
}