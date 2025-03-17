<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

class OrderAddressRepository extends Repository
{
    public function getTableName(): string
    {
        return 'order_address';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->OrderAddress);
    }
}