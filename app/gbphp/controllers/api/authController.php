<?php

namespace App\controllers\api;

use App\main\App;
use App\services\AuthService;
use App\services\UserService;

/**
 * Контроллер api для авторизации
 */
class authController extends controller
{
    /**
     * Сервис авторизации
     * @var AuthService|mixed|null $authService
     */
    protected AuthService $authService;
    /**
     * Сервис пользователей
     * @var UserService|mixed|null $userService
     */
    protected UserService $userService;

    /**
     * Предварительная проверка авторизованости, а также запуск действия
     * @param $action - действие
     * @return mixed
     * @throws \Exception
     */
    public function run($action)
    {
        App::call()->AuthMiddleware->checkLogin(true);
        return parent::run($action);
    }

    /**
     * Конструктор контроллера
     * @param $render - Экземпляр класса для render
     * @param $request - Экземпляр класса для request
     */
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->authService = App::call()->AuthService;
        $this->userService = App::call()->UserService;
    }

    /**
     * Авторизация
     */
    public function loginAction()
    {
        $request = $this->validator->validateJsonData(true);

        $response = $this->authService->auth($request);

        $this->sendJson($response);
    }

    /**
     * Регистрация
     */
    public function registerAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'login' => $request['login'],
            'password' => $request['password'],
        ];

        $responseData = $this->userService->addUser($params);
        $this->sendJson($responseData);
    }
}