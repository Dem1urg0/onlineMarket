<?php

namespace App\controllers;

use App\main\App;
use App\Repositories\CountryRepository;
use App\services\renders\IRender;
use App\services\Request;

/**
 * Контроллер корзины
 *
 * Отвечает за отображение страницы корзины
 *
 * @package App\controllers
 */
class cartController extends Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'index';
    /**
     * Репозиторий стран
     * @var CountryRepository
     */
    protected CountryRepository $countryRepository;

    /**
     * Конструктор контроллера
     * @param IRender $render - Экземпляр класса render
     * @param Request $request - Экземпляр класса request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->countryRepository = App::call()->CountryRepository;
    }

    /**
     * Получение корзины
     * @return string
     */
    public function indexAction()
    {
        return $this->render('cart', []);
    }
}