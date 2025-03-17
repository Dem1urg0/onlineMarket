<?php

namespace App\controllers;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;

class orderController extends Controller
{
    protected $defaultAction = 'all';
    protected $orderService;
    protected $orderRepository;
    protected $countryRepository;

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->orderService = App::call()->OrderService;
        $this->orderRepository = App::call()->OrderRepository;
        $this->countryRepository = App::call()->CountryRepository;
    }

    public function checkoutAction()
    {
        $countries = $this->countryRepository->getAll();
        return $this->render('checkout', ['countries' => $countries]);
    }

    public function allAction()
    {
        if ($user = $this->getSession('user')) {

            $user_id = $user['id'];

            $orders = $this->orderRepository->getOrdersByUserId($user_id);
            $sortOrders = $this->orderService->sortProductsInOrders($orders);

             foreach ($sortOrders as $key => $order) {
                 $sortOrders[$key]['info'] = $this->orderRepository->getOrderInfo($key);
             }
            return $this->render('orders',
                [
                    'orders' => $sortOrders,
                ]);
        } else {
             header('Location: /auth/');
        }
    }

}