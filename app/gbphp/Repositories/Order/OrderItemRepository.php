<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

class OrderItemRepository extends Repository
{
    public function getTableName()
    {
        return 'order_list';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->OrderItem);
    }
}