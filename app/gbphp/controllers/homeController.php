<?php

namespace App\controllers;

/**
 * Контроллер для работы с главной страницей
 *
 * Class homeController
 * @package App\controllers
 */
class homeController extends Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'index';

    /**
     * Рендерит главную страницу
     * @return string
     */
    public function indexAction(){
        return $this->render('home', []);
    }
}