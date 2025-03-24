<?php

namespace App\controllers\admin;

use App\controllers\Controller as mainController;
use App\main\App;

/**
 * Базовый контроллер административной части приложения
 */
class Controller extends mainController
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'index';

    /**
     * Предварительная проверка прав доступа, а также запуск действия
     * @param $action - действие
     * @return mixed
     * @throws \Exception
     */
    public function run($action)
    {
        App::call()->RoleMiddleware->checkAdmin();
        return parent::run($action);
    }

    /**
     * Рендер главное страницы административной части
     * @return string
     */
    public function indexAction()
    {
        return $this->render('admin/index',[]);
    }

}