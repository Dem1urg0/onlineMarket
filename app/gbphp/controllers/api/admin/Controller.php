<?php

namespace App\controllers\api\admin;

use App\controllers\api\Controller as apiController;
use App\main\App;

class Controller extends apiController
{
    public function run($action)
    {
        App::call()->AuthMiddleware->checkApiAuth();
        App::call()->RoleMiddleware->checkApiAdmin();
        return parent::run($action);
    }
}