<?php

namespace App\Repositories;

use App\main\App;

class CodeRepository extends Repository
{
    public function getTableName()
    {
        return 'codes';
    }
    public function getEntityClass()
    {
        return get_class(App::call()->Code);
    }
    public function getSale($code, $country)
    {
        $sql = "SELECT sale FROM codes
                INNER JOIN countries ON countries.id = codes.id
                WHERE countries.country = :country AND codes.code = :code";
        $table = App::call()->db->queryObject($sql, $this->getEntityClass(), [':code' => $code, ':country' => $country]);

        if(!empty($table->sale)){
            return $table->sale;
        } else {
            return false;
        }
    }
}