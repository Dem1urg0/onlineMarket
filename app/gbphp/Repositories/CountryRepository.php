<?php

namespace App\Repositories;

use App\main\App;

/**
 * Класс репозитория стран
 */
class CountryRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName()
    {
        return 'countries';
    }

    /**
     * Получение имени сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->Country);
    }

    /**
     * Получение id страны по имени
     * @param string $country - название страны
     * @return int|bool
     */
    //Можно заменить
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