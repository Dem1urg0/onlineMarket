<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозитория категорий товаров
 */
class GoodCategoryRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'goods_categories';
    }
    /**
     * Метод возвращает имя класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->GoodCategory);
    }
}