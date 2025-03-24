<?php

namespace App\Entities\Good;
use App\Entities\Entity;

/**
 * Класс сущности "Дизайнер товара"
 */
class GoodDesigner extends Entity
{
    /**
     * Id дизайнера товара
     * @var int $id
     */
    public int $id;
    /**
     * Имя дизайнера товара
     * @var string $name
     */
    public string $name;
    /**
     * Количество просмотров
     * @var int $view_count
     */
    public int $touch_count;
}