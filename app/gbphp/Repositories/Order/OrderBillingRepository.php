<?php

namespace App\Repositories\Order;

use App\main\App;
use App\Repositories\Repository;

class OrderBillingRepository extends Repository
{
    public function getTableName(): string
    {
        return 'order_billing';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->OrderBilling);
    }
}