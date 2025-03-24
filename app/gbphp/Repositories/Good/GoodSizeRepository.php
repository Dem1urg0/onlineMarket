<?php

namespace App\Repositories\Good;

use App\main\App;
use App\Repositories\Repository;

/**
 * Класс репозитория размеров товаров
 */
class GoodSizeRepository extends Repository
{
    /**
     * Метод возвращает имя таблицы в БД
     * @return string
     */
    public function getTableName(): string
    {
        return 'sizes';
    }
    /**
     * Метод возвращает имя класса сущности
     * @return string
     */
    public function getEntityClass()
    {
        return get_class(App::call()->GoodSize);
    }
}