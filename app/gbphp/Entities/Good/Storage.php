<?php

namespace App\Entities\Good;

use App\Entities\Entity;

/**
 * Класс сущности Хранилище товаров
 */
class Storage extends Entity
{
    /**
     * Цвет товара
     * @var string $color
     */
    public string $color;
    /**
     * Размер товара
     * @var string $size
     */
    public string $size;
}