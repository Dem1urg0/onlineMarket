<?php

namespace App\controllers\api\admin;

use App\controllers\api\Controller as apiController;
use App\main\App;

/**
 * Базовый абстрактный контроллер api административной части приложения
 */
abstract class Controller extends apiController
{
    /**
     * Предварительная проверка авторизации и прав доступа, а также запуск действия
     * @param $action - действие
     * @return mixed
     * @throws \Exception
     */
    public function run($action)
    {
        App::call()->AuthMiddleware->checkAuth(true);
        App::call()->RoleMiddleware->checkAdmin(true);
        return parent::run($action);
    }
}