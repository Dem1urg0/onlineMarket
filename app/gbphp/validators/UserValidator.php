<?php

namespace App\validators;

use App\main\App;

class UserValidator extends Validator
{
    protected $types = [
        'id',
        'login',
        'role',
    ];

    public function checkUserByLogin($login, $isApi = false)
    {
        if (empty($user = App::call()->UserRepository->getByLogin($login))) {
            $this->throwException('Пользователь не найден', 404, $isApi);
        }
        return $user;
    }
    public function checkUserById($id, $isApi = false)
    {
        if (empty($user = App::call()->UserRepository->getOne($id))){
            $this->throwException('Пользователь не найден', 404, $isApi);
        }
        return $user;
    }
    public function validateLogin($login, $isApi = false)
    {
        if (empty($login)) {
            $this->throwException('Логин не может быть пустым', 400, $isApi);
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $login)) {
            $this->throwException('Логин должен содержать от 3 до 20 символов и состоять из букв, цифр и символа подчеркивания', 400, $isApi);
        }

        if (!empty($user = App::call()->UserRepository->getByLogin($login))) {
            $this->throwException('Пользователь с таким логином уже существует', 400, $isApi);
        }
        return true;
    }

    public function validatePass($password, $isApi = false)
    {
        if (empty($password)) {
            $this->throwException('Пароль не может быть пустым', 400, $isApi);
        }
        if (strlen($password) < 6 || strlen($password) > 50) {
            $this->throwException('Пароль должен содержать от 6 до 50 символов', 400, $isApi);

        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->throwException('Пароль должен содержать хотя бы одну заглавную букву и одну цифру', 400, $isApi);
        }
        return true;
    }
    public function checkUpdateDataEmpty($params, $isApi = false)
    {
        if (empty($params['login']) && empty($params['password']) || empty($params['id'])) {
            $this->throwException('Не переданы данные', 400, $isApi);
        }
        return true;
    }
    public function validateUsersCount($min, $max, $isApi = false)
    {
        if (!is_numeric($min) || !is_numeric($max) || $min < 0 || $max < 0 || $min > $max) {
            $this->throwException('Не верные данные количества пользователей', 400, $isApi);
        }
        return true;
    }
    public function validateInfoData($type, $value, $isApi)
    {
        if (!in_array($type, $this->types) ||
            ($type === 'id' && !is_numeric($value)) ||
            ($type === 'login' && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value)) ||
            ($type === 'role' && (!is_numeric($value) || ($value != 1 && $value != 0)))) {
            $this->throwException('Неверные данные', 400, $isApi);
        }
    }
}