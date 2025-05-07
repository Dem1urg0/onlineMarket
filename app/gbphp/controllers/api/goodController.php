<?php

namespace App\Controllers\api;

use App\main\App;
use App\Repositories\Good\StorageRepository;
use App\Services\GoodService;
use App\validators\GoodValidator;

/**
 * Класс контроллера api для работы с товарами
 * @package App\controllers\api
 */
class goodController extends controller
{
    /**
     * Сервис для работы с товарами
     * @var GoodService
     */
    protected GoodService $goodService;
    /**
     * Репозиторий для работы с хранилищем
     * @var StorageRepository
     */
    protected StorageRepository $storageRepository;
    /**
     * Валидатор для товаров
     * @var GoodValidator
     */
    protected GoodValidator $GoodValidator;

    /**
     * Конструктор контроллера
     * @param $render - Экземпляр класса для render
     * @param $request - Экземпляр класса для request
     */
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->goodService = App::call()->GoodService;
        $this->storageRepository = App::call()->StorageRepository;
        $this->GoodValidator = App::call()->GoodValidator;
    }

    /**
     * Получение всех товаров по фильтрам и пагинации
     */
    public function getFilteredGoodsAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'designers' => $request['designers'] ?? [],
            'brands' => $request['brands'] ?? [],
            'category' => $request['category'] ?? '',
            'sizes' => $request['sizes'] ?? [],
            'gender' => $request['gender'] ?? '',

            'price' => [
                'min' => $request['price']['first'] ?? 0,
                'max' => $request['price']['second'] ?? 'max',
            ],
            'page' => [
                'count' => $request['page']['renderCount'] ?? 6,
                'page' => $request['page']['page'] ?? 1,
            ],
            'sort' => $request['sort'] ?? '',
        ];

        $response = $this->goodService->getFilterGoods($params);

        $this->sendJson($response);
    }

    /**
     * Получение хранилища для товаров
     */
    public function getStorageAction()
    {
        $goods = $this->validator->validateJsonData(true);

        $response = $this->goodService->getStorageForGoods($goods, true);

        $this->sendJson($response);
    }


}