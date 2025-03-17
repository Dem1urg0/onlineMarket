<?php

namespace App\services;
use App\main\App;

class GoodService
{
    protected $goodValidator;
    protected $paginationValidator;

    public function __construct()
    {
        $this->goodValidator = App::call()->GoodValidator;
        $this->paginationValidator = App::call()->PaginationValidator;
    }

    public function checkGood($id)
    {
        $this->goodValidator->checkEmpty($id);

        $item = $this->goodValidator->validateGood($id);

        $storage = $this->goodValidator->checkGoodInStorage($id);

        $params = [
            'good' => $item,
            'storage' => $storage
        ];

        return [
            'msg' => 'Товар найден',
            'success' => true,
            'params' => $params
        ];
    }

    public function addStorageToGoods($goods, $isApi = false)
    {
        $data = [];

        foreach ($goods as $good) {
            $goodId = is_array($good) ? $good['id'] : $good->id;
            $this->goodValidator->validateGood($goodId, $isApi);
            $data[$goodId] = $this->getInfoFromStorage($goodId);
        }

        if ($isApi) {
            return [
                'msg' => 'Характеристики товаров найдены',
                'success' => true,
                'data' => $data,
                'code' => 200
            ];
        } else return $data;

    }

    public function getFilterGoods($params)
    {
        $this->goodValidator->checkEmpty($params, true); //проверка данных

        $this->paginationValidator->validatePagination( //проверка пагинации
            ['page' => $params['page']['page'],
                'count' => $params['page']['count']],
            true);

        if (!empty($params['sort'])) {
            $this->paginationValidator->checkSort($params['sort'], true); //проверка сортировки
        }

        $this->goodValidator->checkPrice(['min' => $params['price']['min'], 'max' => $params['price']['max']], true); //проверка цены

        if (!empty($params['designers'])) {
            $params['designers'] = $this->goodValidator->checkDesigners($params['designers'], true); // проверка дизайнеров и получение их id
        }

        if (!empty($params['brands'])) {
            $params['brands'] = $this->goodValidator->checkBrands($params['brands'], true); // проверка брендов и получение их id
        }

        if (!empty($params['category'])) {
            $params['category'] = $this->goodValidator->validateCategory($params['category'], true); //проверка категории и получение ее id
        }

        if (!empty($params['sizes'])) {
            $params['sizes'] = $this->goodValidator->checkSizes($params['sizes'], true); //проверка размеров и получение их id
        }

        if (!empty($params['gender'])) {
            $this->goodValidator->validateGender($params['gender'], true); //проверка пола
        }

        $params['price'] = $this->goodValidator->normalizePrice($params['price']['min'], $params['price']['max'], true); //нормализация цены

        $data = [
            'min' => $params['price']['min'],
            'max' => $params['price']['max'],
        ];

        if (!empty($params['designers'])) {
            $data['designers'] = $params['designers'];
        }
        if (!empty($params['brands'])) {
            $data['brands'] = $params['brands'];
        }
        if (!empty($params['category'])) {
            $data['category'] = $params['category'];
        }
        if (!empty($params['sizes'])) {
            $data['sizes'] = $params['sizes'];
        }
        if (!empty($params['gender'])) {
            $data['gender'] = $params['gender'];
        }

        $sort = $params['sort'] ?? false;
        $params = [
            'page' => $params['page']['page'],
            'count' => $params['page']['count'],
        ];
        if (!empty($sort)) {
            $params['sort'] = $sort;
        }

        $goods = $this->getWithFilter($params, $data);

        if (empty($goods)) {
            return [
                'msg' => 'Товары не найдены',
                'success' => false,
                'code' => 404
            ];
        }

        $countOfGoods = $this->getCountOfFilter($data);

        $storage = $this->addStorageToGoods($goods);

        return [
            'msg' => 'Товары найдены',
            'success' => true,
            'data' => $goods,
            'storage' => $storage,
            'pagesCount' => ceil($countOfGoods / $params['count']),
            'code' => 200
        ];
    }

    protected function getInfoFromStorage($id)
    {
        return App::call()->StorageRepository->getInfoFromStorage($id);
    }

    protected function getWithFilter($params, $data)
    {
        return App::call()->GoodRepository->getWithFilter($params, $data);
    }

    protected function getCountOfFilter($data)
    {
        return App::call()->GoodRepository->getCountOfFilter($data);
    }
}