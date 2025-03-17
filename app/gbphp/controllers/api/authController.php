<?php

namespace App\controllers\api;

use App\main\App;

class authController extends controller
{
    protected $authService;
    protected $userService;
    public function run($action)
    {
        App::call()->AuthMiddleware->checkApiLogin();
        return parent::run($action);
    }

    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->authService = App::call()->AuthService;
        $this->userService = App::call()->UserService;
    }

    public function loginAction()
    {
        $request = $this->validator->validateJsonData(true);

        $response = $this->authService->auth($request);

        $this->sendJson($response);
    }

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