<?php

namespace App\Entities\Good;
use App\Entities\Entity;

/**
 * Класс сущности "Бренд товара"
 */

class GoodBrand extends Entity
{
    /**
     * ID бренда товара
     * @var int $id
     */
    public int $id;
    /**
     * Название бренда товара
     * @var string $name
     */
    public string $name;
}