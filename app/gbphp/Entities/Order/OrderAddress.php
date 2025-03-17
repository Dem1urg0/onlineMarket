<?php

namespace App\Entities\Order;
use App\Entities\Entity;

class OrderAddress extends Entity
{
    public $order_id;
    public $country_id;
    public $city;
    public $address;
    public $zip;
}