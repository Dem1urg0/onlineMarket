<?php

namespace App\Controllers\api\admin;

use App\Controllers\api\controller as apiController;
use App\main\App;

/**
 * Базовый абстрактный контроллер api административной части приложения
 */
abstract class controller extends apiController
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