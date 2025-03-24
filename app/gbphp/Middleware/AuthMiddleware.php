<?php

namespace App\Middleware;

use App\main\App;

/**
 * Класс middleware авторизованности
 */
class AuthMiddleware extends Middleware
{

    /**
     * Проверка авторизации
     * @return bool
     */
    public function isAuth()
    {
        $user = App::call()->Request->sessionGet('user');

        if (!empty($user['id'])) {
            return $user['id'];
        }
        return false;
    }

    /**
     * Проверка авторизации
     * Если пользователь не авторизован, то выбрасывает исключение
     * @param bool $isApi - флаг API
     * @return bool
     */
    public function checkAuth($isApi = false)
    {
        if (!($id = $this->isAuth())) {
            $this->throwException('Пользователь не авторизован', 401, $isApi);
        }
        return $id;
    }

    /**
     * Проверка не авторизации
     * Если пользователь авторизован, то выбрасывает исключение
     * @param bool $isApi - флаг API
     */
    public function checkLogin($isApi = false)
    {
        if ($this->isAuth()) {
            $this->throwException('Пользователь уже авторизован', 400, $isApi);
        }
    }

}