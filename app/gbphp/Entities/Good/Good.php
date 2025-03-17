<?php

namespace App\Entities\Good;

use App\Entities\Entity;

class Good extends Entity
{
    public $id;
    public $name;
    public $price;
    public $img;
    public $gender;
    public $count = 1;
    public $category_id;
    public $brand_id;
    public $designer_id;
}