<?php

namespace App\Repositories;

use App\main\App;

/**
 * Репозиторий промокодов
 */
class CodeRepository extends Repository
{
    /**
     * Получение имени таблицы в БД
     * @return string
     */
    public function getTableName()
    {
        return 'codes';
    }

    /**
     * Получение имени класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->Code);
    }

    /**
     * Получение скидки по промокоду и стране
     * @param string $code - промокод
     * @param string $country - страна
     * @return int|bool
     */
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