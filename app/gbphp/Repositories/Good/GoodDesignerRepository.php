<?php

namespace App\Repositories\Good;
use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозитория дизайнеров товаров
 */
class GoodDesignerRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'goods_designers';
    }
    /**
     * Метод возвращает имя класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->GoodDesigner);
    }
    /**
     * Метод возвращает массив с топ 5 дизайнерами по просмотрам
     * @return array
     */
    public function getTopDesigners(){
        $sql = "SELECT id, name FROM goods_designers ORDER BY `touch_count` DESC LIMIT 5";

        return $this->db->findAll($sql);
    }
}