<?php

namespace App\controllers;

use App\main\App;
use App\Repositories\Good\GoodBrandRepository;
use App\Repositories\Good\GoodCategoryRepository;
use App\Repositories\Good\GoodDesignerRepository;
use App\Repositories\Good\GoodRepository;
use App\Repositories\Good\GoodSizeRepository;
use App\Repositories\Good\StorageRepository;
use App\services\GoodService;
use App\validators\GoodValidator;


/**
 * Контроллер для работы с товарами
 *
 * Class goodController
 * @package App\controllers
 */
class goodController extends Controller
{
    /**
     * Репозиторий товаров
     * @var GoodRepository|mixed|null $goodRepository
     */
    protected GoodRepository $goodRepository;
    /**
     * Сервис товаров
     * @var GoodService|mixed|null $goodService
     */
    protected GoodService $goodService;
    /**
     * Репозиторий склада
     * @var StorageRepository|mixed|null $storageRepository
     */
    protected StorageRepository $storageRepository;
    /**
     * Репозиторий брендов
     * @var GoodBrandRepository|mixed|null $goodBrandRepository
     */
    protected GoodBrandRepository $goodBrandRepository;
    /**
     * Репозиторий дизайнеров
     * @var GoodDesignerRepository|mixed|null $goodDesignerRepository
     */
    protected GoodDesignerRepository $goodDesignerRepository;
    /**
     * Репозиторий категорий
     * @var GoodCategoryRepository|mixed|null $goodCategoryRepository
     */
    protected GoodCategoryRepository $goodCategoryRepository;
    /**
     * Репозиторий размеров
     * @var GoodSizeRepository|mixed|null $goodSizeRepository
     */
    protected GoodSizeRepository $goodSizeRepository;

    /**
     * Валидатор товаров
     * @var GoodValidator|mixed|null $goodValidator
     */
    protected GoodValidator $goodValidator ;

    /**
     * Конструктор контроллера
     *
     * @param $render - Экземпляр класса render
     * @param $request - Экземпляр класса request

     */
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

        $this->goodValidator = App::call()->GoodValidator;
    }

    /**
     * Выводит страницу с товарами
     * Рендерит либо все товары, либо первую страницу товаров, в зависимости от количества товаров в таблице
     *
     * @return mixed
     */
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


        $storage = $this->goodService->getStorageForGoods($goods);

        $sizes = $this->goodSizeRepository->getAll();
        $topDesigners = $this->goodDesignerRepository->getTopDesigners();

        $countOfFilter = count($goods);
        if ($renderType === 'default') {
            $countOfFilter = $this->goodRepository->getCountOfFilter($data);
        }
        $maxPages = $countOfFilter > 0 ? ceil($countOfFilter / $renderCount) : 1;

        return $this->render(
            'goods', [
            'goods' => $goods,
            'storage' => $storage,

            'topDesigners' => $topDesigners,
            'sizes' => $sizes,

            'maxPrice' => $maxPrice,
            'maxPages' => $maxPages,

            'renderType' => $renderType,

            'gender' => $gender ?? '',
            'category' => $categoryGet ?? '',
            'brand' => $brandGet ?? '',
            'designer' => $designerGet ?? '',
        ]);
    }

    /**
     * Выводит страницу с одним товаром
     *
     * @return mixed
     */
    public function oneAction()
    {
        $this->goodValidator->validateGet();

        $response = $this->goodService->getGoodAndStorage($this->getRequest('id') ?? '');

        return $this->render('good', [
            'good' => $response['params']['good'],
            'storage' => $response['params']['storage']
        ]);
    }
}