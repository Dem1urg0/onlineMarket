<?php

namespace App\validators;

use App\main\App;

class GoodValidator extends Validator
{
    protected $genders = [
        'man',
        'woman',
        'unisex'
    ];

    public function validateGender($gender, $isApi = false)
    {
        if (!in_array($gender, $this->genders)) {
            $this->throwException('Не верный гендер', 400, $isApi);
        }
    }

    public function validateCategory($category, $isApi = false)
    {
        if (empty($data = App::call()->GoodCategoryRepository->getByName($category))) {
            $this->throwException('Invalid category', 400, $isApi);
        }
        return $data->id;
    }

    public function validateBrands($brand, $isApi = false)
    {
        if (empty($data = App::call()->GoodBrandRepository->getByName($brand))) {
            $this->throwException('Invalid brand', 400, $isApi);
        }
        return $data->id;
    }

    public function validateDesigners($designer, $isApi = false)
    {
        if (empty($data = App::call()->GoodDesignerRepository->getByName($designer))) {
            $this->throwException('Invalid designer', 400, $isApi);
        }
        return $data->id;
    }

    public function validateGood($goodId, $isApi = false)
    {
        if (empty($good = App::call()->GoodRepository->getOne($goodId))) {
            $this->throwException('Invalid good: ' . $goodId, 400, $isApi);
        }
        return $good;
    }

    public function checkGoodInStorage($goodId, $isApi = false)
    {
        if (empty($storage = App::call()->StorageRepository->getInfoFromStorage($goodId))) {
            $this->throwException('Товара нет в наличии', 400, $isApi);
        }
        return $storage;
    }

    public function checkPrice($params, $isApi = false)
    {
        if ($params['max'] == 'max') {
            $params['max'] = App::call()->GoodRepository->getMaxPrice();
        }

        if (!is_numeric($params['min']) || !is_numeric($params['max'])) {
            $this->throwException('Не верные данные цены', 400, $isApi);
        }
        if ($params['min'] < 0 || $params['max'] < 0) {
            $this->throwException('Цена не может быть отрицательной', 400, $isApi);
        }
        if ($params['min'] > $params['max']) {
            $this->throwException('Минимальная цена не может быть больше максимальной', 400, $isApi);
        }
    }

    public function checkDesigners($designers, $isApi = false)
    {
        if (!empty($designers)) {
            foreach ($designers as $key => $designer) {
                if (empty($designer_id = App::call()->GoodDesignerRepository->getByName($designer)->id)) {
                    $this->throwException('Дизайнер не найден', 404, $isApi);
                }
                $designers[$key] = $designer_id;
            }
        }
        return $designers;
    }
    public function checkBrands($brands, $isApi = false)
    {
        if (!empty($brands)) {
            foreach ($brands as $key => $brand) {
                if (empty($brand_id = App::call()->GoodBrandRepository->getByName($brand)->id)) {
                    $this->throwException('Бренд не найден', 404, $isApi);
                }
                $brands[$key] = $brand_id;
            }
        }
        return $brands;
    }
    public function checkCategory($category, $isApi = false)
    {
        if (!empty($category)) {
            if (empty($category_id = App::call()->GoodCategoryRepository->getByName($category)->id)) {
                $this->throwException('Категория не найдена', 404, $isApi);
            }
        }
        return $category_id;
    }
    public function checkSizes($sizes, $isApi = false)
    {
        if (!empty($sizes)) {
            foreach ($sizes as $key => $size) {
                if (empty($size_id = App::call()->GoodSizeRepository->getByName($size)->id)) {
                    $this->throwException('Размер не найден', 404, $isApi);
                }
                $sizes[$key] = $size_id;
            }
        }
        return $sizes;
    }
    public function normalizePrice($min, $max)
    {
        $maxPrice = App::call()->GoodRepository->getMaxPrice();

        $max = ($max == 'max' || $max > $maxPrice) ? $maxPrice : $max;
        $min = ($min > $maxPrice) ? $maxPrice : $min;

        if ($min > $max) {
            list($min, $max) = [$max, $min];
        }

        return [
            'min' => $min,
            'max' => $max,
        ];
    }
}