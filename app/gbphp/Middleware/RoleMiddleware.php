<?php

namespace App\Middleware;
use App\main\App;

/**
 * Класс Middleware роли проверяет роль пользователя
 */
class RoleMiddleware extends Middleware
{
    /**
     * Проверка роли пользователя
     * Если роль пользователя не администратор, то выбрасывается исключение
     * @param bool $isApi - флаг API
     */
    public function checkAdmin($isApi = false)
    {
        if ($user = App::call()->Request->sessionGet('user')) {
            if ($user['role'] === 1) {
                return;
            }
        }
        $this->throwException('Нет доступа', 403, $isApi);
    }
}