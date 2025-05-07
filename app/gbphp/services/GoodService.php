<?php

namespace App\Services;

use App\main\App;
use App\validators\GoodValidator;
use App\validators\PaginationValidator;

/**
 * Класс сервиса для работы с товарами
 */
class GoodService
{
    /**
     * Экземпляр класса валидатора товаров
     * @var GoodValidator|mixed|object|null
     */
    protected GoodValidator $goodValidator;

    /**
     * Экземпляр класса валидатора пагинации
     * @var PaginationValidator|mixed|object|null
     */
    protected PaginationValidator $paginationValidator;

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->goodValidator = App::call()->GoodValidator;
        $this->paginationValidator = App::call()->PaginationValidator;
    }

    /**
     * Метод для получения товара и экземпляров в хранилище по id
     * @param $id - id товара
     * @return array
     */
    public function getGoodAndStorage($id)
    {
        $this->goodValidator->checkEmpty($id);

        $this->goodValidator->checkNumeric($id);

        $itemOrigin = $this->goodValidator->validateGood($id);

        $item = $this->getGoodsWithImages([$itemOrigin])[0];

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

    /**
     * Метод для получения хранилища товаров
     * @param $goods - массив товаров
     * @param $isApi - флаг для проверки, является ли запрос из API
     * @return array
     */
    public function getStorageForGoods($goods, $isApi = false)
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

    /**
     * Метод для получения товаров с фильтром
     * @param $params - параметры запроса
     * @return array
     */
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

        $goodsOrigin = $this->getWithFilter($params, $data);

        if (empty($goodsOrigin)) {
            return [
                'msg' => 'Товары не найдены',
                'success' => false,
                'code' => 404
            ];
        }

        $goods = $this->getGoodsWithImages($goodsOrigin, true);

        $countOfGoods = $this->getCountOfFilter($data);

        $storage = $this->getStorageForGoods($goods);

        return [
            'msg' => 'Товары найдены',
            'success' => true,
            'data' => $goods,
            'storage' => $storage,
            'pagesCount' => ceil($countOfGoods / $params['count']),
            'code' => 200
        ];
    }


    /**
     * Метод для получения товаров с подставленными путями к изображениям из манифеста
     * @param $goods - массив товаров
     * @param $isApi - флаг для проверки, является ли запрос из API
     * @return mixed - массив товаров с измененными путями к изображениям
     * @throws \App\Exceptions\apiException
     */
    public function getGoodsWithImages($goods, $isApi = false)
    {
        foreach ($goods as $good) {
            $this->goodValidator->checkEmpty($good, $isApi);
            $goodId = is_array($good) ? $good['id'] : $good->id;
            $this->goodValidator->validateGood($goodId, $isApi);

            $good->img = $this->getAssetPath('images/' .$good->img);
        }
        return $goods;
    }

    /**
     * Метод прослойка для получения информации о товаре из хранилища из репозитория по id
     * @param $id - id товара
     * @return mixed
     */
    protected function getInfoFromStorage($id)
    {
        return App::call()->StorageRepository->getInfoFromStorage($id);
    }

    /**
     * Метод прослойка для получения товаров с фильтром из репозитория по параметрам
     * @param $params - параметры запроса
     * @param $data - данные для фильтрации
     * @return mixed
     */
    protected function getWithFilter($params, $data)
    {
        return App::call()->GoodRepository->getWithFilter($params, $data);
    }

    /**
     * Метод прослойка для получения количества товаров с фильтром из репозитория по данным
     * @param $data - данные для фильтрации
     * @return mixed
     */
    protected function getCountOfFilter($data)
    {
        return App::call()->GoodRepository->getCountOfFilter($data);
    }

    /**
     * Метод прослойка для получения пути к ассету из манифеста
     * @param $key - ключ ассета
     * @return string - путь к ассету
     */
    protected function getAssetPath($key): string
    {
        return App::call()->ManifestService->getAssetPath($key);
    }
}