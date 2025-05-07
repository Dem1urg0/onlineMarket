<?php

namespace App\Controllers\api;

use App\main\App;
use App\Services\OrderService;

/**
 * Класс контроллера api для работы с заказами
 * @package App\controllers\api
 */
class orderController extends controller
{
    /**
     * Id текущего пользователя
     * @var int $user_id
     */
    protected int $user_id;

    /**
     * Сервис для работы с заказами
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * Предварительная проверка авторизации, а также запуск действия
     * @param $action - действие
     * @return mixed|true
     * @throws \Exception
     */
    public function run($action)
    {
        $this->user_id = App::call()->AuthMiddleware->checkAuth(true);
        return parent::run($action);
    }

    /**
     * Конструктор контроллера
     * @param $render - экземпляр класса Render
     * @param $request - экземпляр класса Request
     */
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->orderService = App::call()->OrderService;
    }

    /**
     * Получение всех заказов текущего пользователя
     */
    public function setAction()
    {
        $request = $this->validator->validateJsonData(true);

        $params = [
            'user_id' => $this->user_id,
            'orderInfo' => $request['order'] ?? [],
            'cart' => $request['cart'] ?? [],
        ];

        $response = $this->orderService->orderFill($params);

        $this->sendJson($response);
    }

    /**
     * Удаление заказа
     */
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

    /**
     * Изменение статуса заказа
     */
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