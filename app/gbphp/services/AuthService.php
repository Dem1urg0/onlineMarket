<?php

namespace App\services;

use App\main\App;

class AuthService
{
    protected $userValidator;

    public function __construct()
    {
        $this->userValidator = App::call()->UserValidator;
    }

    public function auth($params)
    {

        $this->userValidator->checkEmpty($params, true);
        $this->userValidator->checkEmpty($params['login'], true);
        $this->userValidator->checkEmpty($params['password'], true);

        $userObj = $this->userValidator->checkUserByLogin($params['login'] , true);

        $userData = get_object_vars($userObj);

        if (password_verify($params['password'], $userData['password'])) {
            $userSession = [
                'id' => $userData['id'],
                'login' => $userData['login'],
                'role' => $userData['role']
            ];
        } else  return [
            'code' => 400,
            'msg' => 'Не верные данные',
            'success' => false,
        ];

        $this->sessionSet('user', $userSession);

        return [
            'code' => 200,
            'msg' => 'Успешно',
            'success' => true,
        ];
    }

    protected function sessionSet($name, $value)
    {
        return App::call()->Request->sessionSet($name, $value);
    }
}