<?php

namespace App\services;

use App\main\App;

class UserService
{
    protected $userValidator;
    protected $paginationValidator;
    protected $orderValidator;

    public function __construct()
    {
        $this->userValidator = App::call()->UserValidator;
        $this->paginationValidator = App::call()->PaginationValidator;
        $this->orderValidator = App::call()->OrderValidator;
    }

    public function addUser($params)
    {
        $this->userValidator->checkEmpty($params['login'], true);
        $this->userValidator->checkEmpty($params['password'], true);

        $this->userValidator->validateLogin($params['login'], true);
        $this->userValidator->validatePass($params['password'], true);

        $user = $this->getUserObj();

        if (!empty($params['id'])) {
            $user->id = $params['id'];
        }

        $user->login = $params['login'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);

        $this->userSave($user);

        return [
            'msg' => 'Пользователь сохранен',
            'success' => true,
            'code' => 200
        ];
    }

    public function updateUser($params)
    {
        $this->userValidator->checkUpdateDataEmpty($params, true);

        $user = $this->userValidator->checkUserById($params['id'], true);

        if (!empty($params['login'])) {
            $this->userValidator->validateLogin($params['login'], true);
        }

        if (!empty($params['password'])) {
            $this->userValidator->validatePass($params['password'], true);
        }

        $user->id = $params['id'];

        if (!empty($params['login'])) {
            $user->login = $params['login'];
        }

        if (!empty($params['password'])) {
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        }

        $this->userSave($user);
        return [
            'msg' => 'Пользователь сохранен',
            'success' => true,
            'code' => 200
        ];
    }

    public function getFilter($params)
    {
        $this->userValidator->checkEmpty($params, true);

        $this->paginationValidator->validatePagination($params['renderInfo'], true);

        $this->userValidator->validateUsersCount($params['ordersInfo']['min'], $params['ordersInfo']['max'], true);

        //search by info
        if (!empty($type = $params['searchByInfo']['type']) && !empty($value= $params['searchByInfo']['value'])) {
            $this->userValidator->validateInfoData($type, $value, true);
        }

        //search by orders
        $ordersLimits = $this->orderValidator->normalizeOrdersCount($params['ordersInfo']['min'], $params['ordersInfo']['max'], true);

        // page & render count
        $page = $params['renderInfo']['page'];
        $count = $params['renderInfo']['count'];

        $data = [
            'min' => $ordersLimits['min'],
            'max' =>  $ordersLimits['max'],
        ];

        if (!empty($type)) {
            $data['value'] = '%' . $value . '%';
        }

        $paramsForDB = [
            'type' => $type ?? '',
            'page' => $page,
            'count' => $count,
        ];

        $users = $this->getUsersWithFilter($paramsForDB, $data);

        if (empty($users)) {
            return [
                'msg' => 'Нет данных',
                'success' => false,
                'code' => 400
            ];
        }
        $usersCount = $this->getCountOfFilterUSers($data, $type ?? '');

        return [
            'msg' => 'Данные получены',
            'success' => true,
            'code' => 200,
            'data' => $users,
            'pagesCount' => ceil($usersCount / $count),
        ];
    }

    protected function getCountOfFilterUsers($params, $data)
    {
        return App::call()->UserRepository->getCountOfFilter($params, $data);
    }

    protected function getUsersWithFilter($params, $data)
    {
        return App::call()->UserRepository->getWithFilter($params, $data);
    }

    protected function userSave($user)
    {
        App::call()->UserRepository->save($user);
    }

    protected function getUserObj()
    {
        return App::call()->User;
    }

}