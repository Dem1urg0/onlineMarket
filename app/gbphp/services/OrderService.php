<?php

namespace App\services;

use App\main\App;

class OrderService
{
    private $orderStatuses = [
        'created', 'in delivery', 'delivered'
    ];

    protected $orderValidator;
    protected $countryValidator;
    protected $codeValidator;

    public function __construct()
    {
        $this->orderValidator = App::call()->OrderValidator;
        $this->countryValidator = App::call()->CountryValidator;
        $this->codeValidator = App::call()->CodeValidator;
    }

    public function orderFill($params)
    {
        $this->orderValidator->checkEmpty($params['cart'], true);

        $this->orderValidator->checkEmpty($params['orderInfo'], true);

        //проверка страны
        $id_country = $this->countryValidator->validateCountry($params['orderInfo']['address']['country'], true);

        //проверка кода
        $sale = $this->codeValidator->validateCode($params['orderInfo']['code']['value'], $params['orderInfo']['address']['country'], true);

        $order = $this->getOrderObj();
        $orderItem = $this->getOrderItemObj();
        $orderInfo = $this->getOrderInfoObj();
        $orderBilling = $this->getOrderBillingObj();
        $orderAddress = $this->getOrderAddressObj();

        $order->user_id = $params['user_id'];
        $order_id = $this->saveOrder($order);

        //заполнение billing и сохранение
        $orderBilling->order_id = $order_id;
        $orderBilling->first = $params['orderInfo']['billing']['first'];
        $orderBilling->second = $params['orderInfo']['billing']['second'];
        if (!empty($params['orderInfo']['billing']['sur'])) {
            $orderBilling->sur = $params['orderInfo']['billing']['sur'];
        }
        $billing_id = $this->saveOrderBilling($orderBilling);

        //заполнение address и сохранение
        $orderAddress->country_id = $id_country;
        $orderAddress->order_id = $order_id;
        $orderAddress->city = $params['orderInfo']['address']['city'];
        $orderAddress->address = $params['orderInfo']['address']['address'];
        $orderAddress->zip = $params['orderInfo']['address']['zip'];
        $address_id = $this->saveOrderAddress($orderAddress);

        //заполнение orderInfo и сохранение
        $orderInfo->order_id = $order_id;
        $orderInfo->address_id = $address_id;
        $orderInfo->billing_id = $billing_id;
        $orderInfo->shipping = $params['orderInfo']['shipping']['method'];
        if (!empty($sale)) {
            $orderInfo->sale = $sale;
        }
        $this->saveOrderInfo($orderInfo);

        $orderItem->order_id = $order_id;
        foreach ($params['cart'] as $item) {
            $orderItem->good_id = $item['id'];
            $orderItem->count = $item['count'];
            $orderItem->size = $item['size'];
            $orderItem->color = $item['color'];
            $this->saveOrderItem($orderItem);
        }
        $this->sessionSet('cart', []);
        return [
            'code' => 200,
            'msg' => 'Заказ создан',
            'success' => true,
        ];
    }

    public function sortProductsInOrders($orders)
    {
        $this->orderValidator->checkEmpty($orders, true);

        $orderSort = [];
        $orders_id = [];

        foreach ($orders as $order) {
            $orders_id[] = $order->id;
        }
        $orders_id = array_unique($orders_id);

        foreach ($orders_id as $id) {
            foreach ($orders as $order) {
                if ($order->id == $id) {
                    $orderSort[$id]['products'][] = [
                        'name' => $order->name, //item name/price/ ...
                        'price' => $order->price,
                        'color' => $order->color,
                        'size' => $order->size,
                        'count' => $order->count,
                    ];
                }
            }
        }
        return $orderSort;
    }

    public function deleteOrder($params)
    {
        $this->orderValidator->validateOrderAccess($params['user_id'], $params['order_id'], true);

        $this->orderValidator->checkEmpty($params['order_id'], true);

        $this->deleteOrderFromDB($params['order_id']);

        return [
            'success' => true,
            'msg' => 'Заказ удален',
            'code' => 200
        ];

    }

    public function changeOrderStatus($params)
    {
        $this->orderValidator->checkEmpty($params['order_id'], true);
        $this->orderValidator->checkEmpty($params['status'], true);

        if (!in_array($params['status'], $this->orderStatuses)) {
            return [
                'success' => false,
                'msg' => 'Не верные данные',
                'code' => 400
            ];
        }

        $order = $this->getOrderById($params['order_id']);

        $this->orderValidator->checkStatus($order->status, $params['status'], true);

        $this->orderValidator->validateOrderAccess($params['user_id'], $params['order_id'], $order->status, true);

        $this->changeOrderStatusInDB($params['order_id'], $params['status']);

        return [
            'success' => true,
            'msg' => 'Статус изменен',
            'code' => 200
        ];
    }

    protected function getOrderById($id)
    {
        return App::call()->OrderRepository->getOne($id);
    }

    protected function getOrderObj()
    {
        return App::call()->Order;
    }

    protected function getOrderItemObj()
    {
        return App::call()->OrderItem;
    }

    protected function getOrderInfoObj()
    {
        return App::call()->OrderInfo;
    }

    protected function getOrderBillingObj()
    {
        return App::call()->OrderBilling;
    }

    protected function getOrderAddressObj()
    {
        return App::call()->OrderAddress;
    }

    protected function saveOrder($order)
    {
        return App::call()->OrderRepository->save($order);
    }

    protected function saveOrderItem($orderItem)
    {
        return App::call()->OrderItemRepository->save($orderItem);
    }

    protected function saveOrderBilling($orderBilling)
    {
        return App::call()->OrderBillingRepository->save($orderBilling);
    }

    protected function saveOrderAddress($orderAddress)
    {
        return App::call()->OrderAddressRepository->save($orderAddress);
    }

    protected function saveOrderInfo($orderInfo)
    {
        return App::call()->OrderInfoRepository->save($orderInfo);
    }

    protected function sessionSet($name, $value)
    {
        return App::call()->Request->sessionSet($name, $value);
    }

    protected function sessionGet($name)
    {
        return App::call()->Request->sessionGet($name);
    }

    protected function deleteOrderFromDB($id)
    {
        return App::call()->OrderRepository->deleteOrder($id);
    }

    protected function changeOrderStatusInDB($id, $status)
    {
        return App::call()->OrderRepository->changeOrderStatus($id, $status);
    }
}