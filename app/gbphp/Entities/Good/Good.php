<?php

namespace App\Entities\Good;

use App\Entities\Entity;

/**
 * Класс сущности товара
 */
class Good extends Entity
{
    /**
     * ID товара
     * @var int $id
     */
    public int $id;
    /**
     * Название товара
     * @var string $name
     */
    public string $name;
    /**
     * Цена товара
     * @var int $price
     */
    public int $price;
    /**
     * Путь изображения товара
     * @var string $img
     */
    public string $img;
    /**
     * Пол для которого предназначен товар
     * @var string $gender
     */
    public string $gender;
    /**
     * Количество товара
     * @var int $count
     */
    public int $count = 1;
    /**
     * ID категории товара
     * @var int $category_id
     */
    public int $category_id;
    /**
     * ID бренда товара
     * @var int $brand_id
     */
    public int $brand_id;
    /**
     * ID дизайнера товара
     * @var int $designer_id
     */
    public int $designer_id;
}