<?php

namespace App\controllers\api;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;

class userController extends Controller
{
    protected $user_id;
    protected $userService;

    public function run($action)
    {
        $this->user_id = App::call()->AuthMiddleware->checkApiAuth();
        return parent::run($action);
    }

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->userService = App::call()->UserService;
    }

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