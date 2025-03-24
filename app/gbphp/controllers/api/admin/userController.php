<?php

namespace App\controllers\api\admin;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;
use App\services\UserService;

/**
 * Класс контроллера api работы с пользователями для администратора
 */
class userController extends controller
{
    /**
     * Действие по умолчанию
     * @var string $defaultAction
     */
    protected string $defaultAction = 'getFilteredUsers';

    /**
     * Сервис работы с пользователями
     * @var UserService $userService
     */
    protected UserService $userService;

    /**
     * Конструктор контроллера
     * @param IRender $render - Экземпляр класса для render
     * @param Request $request - Экземпляр класса для request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->userService = App::call()->UserService;
    }

    /**
     * Получение списка пользователей с фильтрацией и пагинацией
     */
    public function getFilteredUsersAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'ordersInfo' => [
                'min' => $request['searchByOrders']['first'] ?? 0,
                'max' => $request['searchByOrders']['second'] ?? 'max',
            ],
            'searchByInfo' => [
                'type' => $request['searchByParams']['type'] ?? '',
                'value' => $request['searchByParams']['value'] ?? '',
            ],
            'renderInfo' => [
                'page' => $request['pageInfo']['page'] ?? 1,
                'count' => min(15, max(1, $request['pageInfo']['renderCount'] ?? 5)),
            ]
        ];

        $response = $this->userService->getFilter($params);
        $this->sendJson($response);
    }
}