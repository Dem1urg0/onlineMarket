<?php

namespace App\Services;

use App\main\App;
use App\validators\OrderValidator;
use App\validators\PaginationValidator;
use App\validators\UserValidator;

/**
 * Класс сервиса для работы с пользователями
 */
class UserService
{
    /**
     * @var UserValidator|mixed|object|null $userValidator - объект валидатора пользователей
     */
    protected UserValidator $userValidator;

    /**
     * @var PaginationValidator|mixed|object|null $paginationValidator - объект валидатора пагинации
     */
    protected PaginationValidator $paginationValidator;

    /**
     * @var OrderValidator|mixed|object|null $orderValidator - объект валидатора заказов
     */
    protected OrderValidator $orderValidator;

    /**
     * Конструктор сервиса
     */
    public function __construct()
    {
        $this->userValidator = App::call()->UserValidator;
        $this->paginationValidator = App::call()->PaginationValidator;
        $this->orderValidator = App::call()->OrderValidator;
    }

    /**
     * Метод добавления пользователя
     * @param $params - параметры пользователя
     * @return array
     */
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

    /**
     * Метод обновления данных пользователя
     * @param $params - параметры пользователя
     * @return array
     */
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

    /**
     * Получение пользователей с фильтром и пагинацией
     * @param $params - параметры фильтрации и пагинации
     * @return array
     */
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
            $data['value'] = isset($value) && $value !== '' ? '%' . $value . '%' : null;
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

    /**
     * Метод прослойка для получения количества пользователей с фильтром из репозитория
     * @param $params - параметры фильтрации
     * @param $data - данные для фильтрации
     * @return mixed
     */
    protected function getCountOfFilterUsers($params, $data)
    {
        return App::call()->UserRepository->getCountOfFilter($params, $data);
    }

    /**
     * Метод прослойка для получения пользователей с фильтром из репозитория
     * @param $params - параметры фильтрации
     * @param $data - данные для фильтрации
     * @return mixed
     */
    protected function getUsersWithFilter($params, $data)
    {
        return App::call()->UserRepository->getWithFilter($params, $data);
    }

    /**
     * Метод сохранения пользователя
     * @param $user - объект пользователя
     * @return void
     */
    protected function userSave($user)
    {
        App::call()->UserRepository->save($user);
    }

    /**
     * Метод получения объекта пользователя
     * @return mixed|object|null - объект пользователя
     */
    protected function getUserObj()
    {
        return App::call()->User;
    }

}