<?php

namespace App\Repositories;

use App\main\App;

class CountryRepository extends Repository
{
    public function getTableName()
    {
        return 'countries';
    }

    public function getEntityClass()
    {
        return get_class(App::call()->Country);
    }

    public function getIdCountryByName($country)
    {
        $sql = "SELECT id FROM `countries` WHERE country = :country";
        $res =$this->db->find($sql, [':country' => $country]);
        if(!empty($res['id'])){
            return $res['id'];
        } else {
            return false;
        }
    }
}