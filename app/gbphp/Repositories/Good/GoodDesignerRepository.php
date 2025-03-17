<?php

namespace App\Repositories\Good;
use App\main\App;
use App\Repositories\Repository;

class GoodDesignerRepository extends Repository
{
    public function getTableName(): string
    {
        return 'goods_designers';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->GoodDesigner);
    }
    public function getTopDesigners(){
        $sql = "SELECT id, name FROM goods_designers ORDER BY `touch_count` DESC LIMIT 5";

        return $this->db->findAll($sql);
    }
}