<?php

namespace App\controllers\api;

use App\main\App;

class goodController extends Controller
{
    protected $goodService;
    protected $storageRepository;
    protected $GoodValidator;

    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->goodService = App::call()->GoodService;
        $this->storageRepository = App::call()->StorageRepository;
        $this->GoodValidator = App::call()->GoodValidator;
    }

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

    public function getStorageAction()
    {
        $goods = $this->validator->validateJsonData(true);

        $response = $this->goodService->addStorageToGoods($goods, true);

        $this->sendJson($response);
    }


}