<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

class OrderInfoRepository extends Repository
{
    public function getTableName(): string
    {
        return 'order_info';
    }
    public function getEntityClass()
    {
        get_class(App::call()->OrderInfo);
    }
}