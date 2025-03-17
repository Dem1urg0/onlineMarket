<?php

namespace App\controllers\admin;

use App\main\App;

class ordersController extends controller
{
    protected $defaultAction = 'all';

    public function allAction()
    {
        $orders = App::call()->OrderRepository->getAllOrders();
        $sortOrders = App::call()->OrderService->sortProductsInOrders($orders);
        foreach ($sortOrders as $key => $order) {
            $sortOrders[$key]['info'] = App::call()->OrderRepository->getOrderInfo($key);
        }
        return $this->render('/admin/orders',
            [
                'orders' => $sortOrders,
            ]);
    }
}