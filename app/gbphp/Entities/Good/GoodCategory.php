<?php

namespace App\Entities\Good;
use App\Entities\Entity;

/**
 * Класс сущности "Категория товара"
 */
class GoodCategory extends Entity
{
    /**
     * ID категории товара
     * @var int $id
     */
    public int $id;
    /**
     * Название категории товара
     * @var string $name
     */
    public string $name;
}