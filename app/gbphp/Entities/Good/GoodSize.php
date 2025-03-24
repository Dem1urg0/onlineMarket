<?php

namespace App\Entities\Good;

use App\Entities\Entity;

/**
 * Класс сущности "Размер товара"
 */
class GoodSize extends Entity
{
    /**
     * Id размера товара
     * @var int
     */
    public int $id;
    /**
     * Название размера товара
     * @var string
     */
    public string $name;
}