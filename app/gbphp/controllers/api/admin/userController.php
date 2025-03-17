<?php

namespace App\controllers\api\admin;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;

class userController extends controller
{
    protected $defaultAction = 'getFilteredUsers';
    protected $userService;

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->userService = App::call()->UserService;
    }

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