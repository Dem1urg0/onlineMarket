<?php

namespace App\services;

use App\main\App;
use App\validators\UserValidator;

/**
 * Класс сервиса авторизации
 */
class AuthService
{
    /**
     * Экземпляр валидатора пользователя
     * @var UserValidator|mixed|object|null $userValidator
     */
    protected UserValidator $userValidator;

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->userValidator = App::call()->UserValidator;
    }

    /**
     * Метод авторизации
     * @param array $params - массив с данными пользователя
     * @return array
     */
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

    /**
     * Метод прослойка для установки переменной в сессию
     * @param $name - имя переменной
     * @param $value - значение
     */
    protected function sessionSet($name, $value)
    {
        return App::call()->Request->sessionSet($name, $value);
    }
}