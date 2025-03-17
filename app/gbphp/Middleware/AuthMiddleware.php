<?php

namespace App\Middleware;

use App\Exceptions\apiException;
use App\main\App;

class AuthMiddleware
{
    public function isAuth()
    {
        $user = App::call()->Request->sessionGet('user');

        if (!empty($user['id'])) {
            return $user['id'];
        }
        return false;
    }

    public function checkApiAuth()
    {
        if (!($id = $this->isAuth())) {
            throw new ApiException('Пользователь не авторизован', 401);
        }
        return $id;
    }

    public function checkApiLogin()
    {
        if ($this->isAuth()) {
            throw new ApiException('Пользователь уже авторизован', 400);
        }
    }

}