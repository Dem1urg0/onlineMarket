<?php

namespace App\Entities;

/**
 * Класс сущности Страна
 */
class Country extends Entity
{
    /**
     * id страны
     * @var int $id
     */
    public int $id;
    /**
     * Название страны
     * @var string $country
     */
    public string $country;
}