<?php

namespace App\Controllers\api;

use App\main\App;
use App\Services\renders\IRender;
use App\Services\Request;
use App\Services\UserService;

/**
 * Класс контроллера api для работы с пользователями
 * @package App\controllers\api
 */
class userController extends controller
{
    /**
     * Id пользователя
     * @var int $user_id
     */
    protected int $user_id;

    /**
     * Сервис работы с пользователями
     * @var UserService $userService
     */
    protected UserService $userService;

    /**
     * Предварительная проверка авторизации, а также запуск действия
     * @param $action - действие
     * @return mixed
     * @throws \Exception
     */
    public function run($action)
    {
        $this->user_id = App::call()->AuthMiddleware->checkAuth(true);
        return parent::run($action);
    }

    /**
     * Конструктор контроллера
     * @param IRender $render - экземпляр класса render
     * @param Request $request - экземпляр класса request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->userService = App::call()->UserService;
    }

    /**
     * Метод изменения данных пользователя
     */
    public function UserEditAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'id' => $this->user_id,
        ];

        if (!empty($request['login'])) {
            $params['login'] = $request['login'];
        }
        if (!empty($request['password'])) {
            $params['password'] = $request['password'];
        }
        $response = $this->userService->updateUser($params);

        $this->sendJson($response);
    }

    /**
     * Метод получения данных пользователя
     */
    public function userDataAction()
    {
        $request = $this->validator->validateJsonData(true);
        $userData = $this->getSession('user')[$request['data']];
        if (empty($userData)) {
            $userData = [
                'id' => 0,
                'login' => 'guest',
                'role' => '1'
            ];
        }
        $this->sendJson($userData);
    }
}