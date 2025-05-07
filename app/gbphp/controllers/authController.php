<?php

namespace App\Controllers;

use App\main\App;

/**
 * Контроллер авторизации
 *
 * Определяет методы для работы с авторизацией пользователей.
 *
 * @package App\controllers
 */
class authController extends Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'index';

    /**
     * Отображает форму авторизации или перенаправляет на страницу пользователя
     *
     * @return string
     */
    public function indexAction()
    {
        if (!empty($id = App::call()->AuthMiddleware->isAuth())) {
            header('Location: /user/one?id=' . $id);
        }
        return $this->render('login', []);
    }

    /**
     * Выход пользователя из системы
     * @return void
     */
    public function logoutAction()
    {
        $this->deleteFromSession('user');
        header('Location: /auth/');
    }
}
