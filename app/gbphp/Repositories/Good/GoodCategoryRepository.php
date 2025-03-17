<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

class GoodCategoryRepository extends Repository
{
    public function getTableName(): string
    {
        return 'goods_categories';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->GoodCategory);
    }
}