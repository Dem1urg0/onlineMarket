<?php

namespace App\controllers\api;

use App\main\App;

class orderController extends controller
{
    protected $user_id;
    protected $orderService;

    public function run($action)
    {
        $this->user_id = App::call()->AuthMiddleware->checkApiAuth();
        return parent::run($action);
    }
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->orderService = App::call()->OrderService;
    }

    public function setAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'user_id' => $this->user_id,
            'orderInfo' => $request['order'] ?? [],
            'cart' => $request['cart'] ?? [],
        ];

        $response = $this->orderService->orderFill($params, true);

        $this->sendJson($response);
    }

    public function deleteAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'user_id' => $this->user_id,
            'order_id' => $request['order_id'] ?? '',
        ];

        $response =$this->orderService->deleteOrder($params);

        $this->sendJson($response);
    }

    public function changeStatusAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'user_id' => $this->user_id,
            'order_id' => $request['order_id'] ?? '',
            'status' => $request['status'] ?? '',
        ];

        $response = $this->orderService->changeOrderStatus($params);

        $this->sendJson($response);
    }
}