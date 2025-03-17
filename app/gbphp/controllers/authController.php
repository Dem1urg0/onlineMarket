<?php

namespace App\controllers;

use App\main\App;

class authController extends Controller
{
    protected $defaultAction = 'index';

    public function indexAction()
    {
        if (!empty($user = $this->getSession('user'))) {
            header('Location: /user/one?id=' . $user['id']);
        }
        return $this->render('login', []);
    }

    public function logoutAction()
    {
        $this->deleteFromSession('user');
        header('Location: /auth/');
    }
}
