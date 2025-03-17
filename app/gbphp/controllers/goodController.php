<?php

namespace App\controllers;

use App\main\App;


class goodController extends Controller
{
    protected $goodRepository;
    protected $goodService;
    protected $storageRepository;
    protected $goodBrandRepository;
    protected $goodDesignerRepository;
    protected $goodCategoryRepository;
    protected $goodSizeRepository;

    public function __construct($render, $request)
    {
        parent::__construct($render, $request);

        $this->validator = App::call()->GoodValidator;
        $this->goodService = App::call()->GoodService;
        $this->goodRepository = App::call()->GoodRepository;
        $this->storageRepository = App::call()->StorageRepository;
        $this->goodBrandRepository = App::call()->GoodBrandRepository;
        $this->goodDesignerRepository = App::call()->GoodDesignerRepository;
        $this->goodCategoryRepository = App::call()->GoodCategoryRepository;
        $this->goodSizeRepository = App::call()->GoodSizeRepository;
    }

    public function allAction()
    {
        $renderCount = 6;
        $manyItemsThreshold = 1;

        $maxPrice = $this->goodRepository->getMaxPrice();
        $goodsCount = $this->goodRepository->getCountOfAll();


        $genderGet = $this->getRequest('gender') ?? '';
        $categoryGet = $this->getRequest('category') ?? '';
        $brandGet = $this->getRequest('brands') ?? '';
        $designerGet = $this->getRequest('designers') ?? '';


        $data = [
            'min' => 0,
            'max' => $maxPrice
        ];
        $params = [];

        if ($genderGet) {
            $this->validator->validateGender($genderGet);
            $gender = $genderGet;
        }
        if ($categoryGet) {
            $category = $this->validator->validateCategory($categoryGet);
        }
        if ($brandGet) {
            $brand = $this->validator->validateBrands($brandGet);
        }
        if ($designerGet) {
            $designer = $this->validator->validateDesigners($designerGet);
        }

        $renderType = $goodsCount > $manyItemsThreshold ? 'default' : 'many';

        if ($renderType === 'default') {
            $params['page'] = 1;
            $params['count'] = $renderCount;
            if (!empty($gender)) {
                $data['gender'] = $gender;
            }
            if (!empty($category)) {
                $data['category'] = $category;
            }
            if (!empty($brand)) {
                $data['brands'] = [$brand];
            }
            if (!empty($designer)) {
                $data['designers'] = [$designer];
            }
        }

        $goods = $this->goodRepository->getWithFilter($params, $data);


        $storage = $this->goodService->addStorageToGoods($goods);

        $categories = $this->goodCategoryRepository->getAll();
        $brands = $this->goodBrandRepository->getAll();
        $sizes = $this->goodSizeRepository->getAll();
        $designers = $this->goodDesignerRepository->getAll();
        $topDesigners = $this->goodDesignerRepository->getTopDesigners();

        $countOfFilter = count($goods);
        if ($renderType === 'default') {
            $countOfFilter = $this->goodRepository->getCountOfFilter($data);
        }
        $maxPages = ceil($countOfFilter / $renderCount);

        return $this->render(
            'goods', [
            'goods' => $goods,
            'storage' => $storage,

            'categories' => $categories,
            'brands' => $brands,
            'designers' => $designers,
            'topDesigners' => $topDesigners,
            'sizes' => $sizes,

            'maxPrice' => $maxPrice,
            'maxPages' => $maxPages ?? null,

            'renderType' => $renderType,

            'gender' => $gender ?? '',
            'category' => $categoryGet ?? '',
            'brand' => $brandGet ?? '',
            'designer' => $designerGet ?? '',
        ]);
    }

    public function oneAction()
    {
        if (empty($id = $this->getRequest('id'))) {
            throw new \Exception("Не передан id товара", 400);
        }

        if (($response = $this->goodService->checkGood($id))['success'] === false) {
            throw new \Exception("Товар не найден", 404);
        }

        return $this->render('good', [
            'good' => $response['params']['good'],
            'storage' => $response['params']['storage']
        ]);
    }
}