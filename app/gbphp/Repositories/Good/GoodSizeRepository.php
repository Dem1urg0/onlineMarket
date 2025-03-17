<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

class GoodSizeRepository extends Repository
{
    public function getTableName(): string
    {
        return 'sizes';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->GoodSize);
    }
}