<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

class GoodBrandRepository extends Repository
{
    public function getTableName(): string
    {
        return 'goods_brands';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->GoodBrand);
    }
}