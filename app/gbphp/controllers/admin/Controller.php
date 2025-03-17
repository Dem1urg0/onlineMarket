<?php

namespace App\controllers\admin;

use App\controllers\Controller as mainController;
use App\main\App;

class Controller extends mainController
{
    protected $defaultAction = 'index';

    public function run($action)
    {
        if (App::call()->RoleMiddleware->checkAdmin()) {
            return header('Location: /error?code=403');
        }
        return parent::run($action);
    }

    public function indexAction()
    {
        return $this->render('admin/index',[]);
    }

}