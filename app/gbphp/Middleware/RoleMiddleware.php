<?php

namespace App\Middleware;

use App\Exceptions\apiException;
use App\main\App;

class RoleMiddleware
{
    public function checkAdmin()
    {
        if ($user = App::call()->Request->sessionGet('user')) {
            if ($user['role'] === 'admin') {
                return false;
            }
        } else return true;
    }
    public function checkApiAdmin(){
        if ($this->checkAdmin()){
            throw new ApiException('Доступ запрещен', 403);
        }
    }
}