<?php

namespace App\services;

use App\main\App;
use App\validators\CodeValidator;
use App\validators\CountryValidator;
use App\validators\OrderValidator;

/**
 * Класс сервиса для работы с заказами
 */
class OrderService
{
    /**
     * @var array|string[] $orderStatuses массив статусов заказа
     */
    private array $orderStatuses = [
        'created', 'in delivery', 'delivered'
    ];

    /**
     * @var OrderValidator|mixed|object|null $orderValidator объект валидатора заказа
     */
    protected OrderValidator $orderValidator;

    /**
     * @var CountryValidator|mixed|object|null $countryValidator объект валидатора страны
     */
    protected CountryValidator $countryValidator;

    /**
     * @var CodeValidator|mixed|object|null $codeValidator объект валидатора кода
     */
    protected CodeValidator $codeValidator;

    /**
     * Конструктор сервиса
     */
    public function __construct()
    {
        $this->orderValidator = App::call()->OrderValidator;
        $this->countryValidator = App::call()->CountryValidator;
        $this->codeValidator = App::call()->CodeValidator;
    }

    /**
     * Отправка заказа
     * @param $params - параметры заказа
     * @return array - результат выполнения метода
     * @throws \App\Exceptions\apiException
     */
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

    /**
     * Сортировка продуктов в заказы
     * @param $orders - заказы
     * @return array
     */
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

    /**
     * Получение заказа / заказов
     * @param $params - параметры
     * @param $isApi - флаг для API
     * @return array
     */
    public function getOrders($params, $isApi = false)
    {
        if (empty($params['id'])) {
            $this->checkAdmin();
            $orders = App::call()->OrderRepository->getAllOrders();
        } else {
            $orders = App::call()->OrderRepository->getOrdersByUserId($params['id']);
        }

        $sortOrders = $this->sortProductsInOrders($orders);

        foreach ($sortOrders as $key => $order) {
            $sortOrders[$key]['info'] = App::call()->OrderRepository->getOrderInfo($key);
        }

        if ($isApi) {
            return [
                'data' => $sortOrders,
                'code' => 200,
                'success' => true,
            ];
        }
        return $sortOrders;
    }

    /**
     * Удаление заказа
     * @param $params - параметры
     * @return array
     */
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

    /**
     * Изменение статуса заказа
     * @param $params - параметры
     * @return array
     */
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

    /**
     * Проверка на админа
     */
    protected function checkAdmin()
    {
        App::call()->RoleMiddleware->checkAdmin();
    }

    /**
     * Получение заказа по id
     * @param $id - id заказа
     * @return mixed
     */
    protected function getOrderById($id)
    {
        return App::call()->OrderRepository->getOne($id);
    }

    /**
     * Получение объекта сущности заказа
     * @return mixed|object|null - объект заказа
     */
    protected function getOrderObj()
    {
        return App::call()->Order;
    }

    /**
     * Получение объекта сущности продукта в заказе
     * @return mixed|object|null - объект продукта в заказе
     */
    protected function getOrderItemObj()
    {
        return App::call()->OrderItem;
    }

    /**
     * Получение объекта сущности информации о заказе
     * @return mixed|object|null - объект сущности информации о заказе
     */
    protected function getOrderInfoObj()
    {
        return App::call()->OrderInfo;
    }

    /**
     * Получение объекта сущности биллинга
     * @return mixed|object|null - объект сущности биллинга
     */
    protected function getOrderBillingObj()
    {
        return App::call()->OrderBilling;
    }

    /**
     * Получение объекта сущности адреса
     * @return mixed|object|null - объект сущности адреса
     */
    protected function getOrderAddressObj()
    {
        return App::call()->OrderAddress;
    }

    /**
     * Сохранение заказа в таблицу заказов
     * @param $order - заказ
     * @return mixed
     */
    protected function saveOrder($order)
    {
        return App::call()->OrderRepository->save($order);
    }

    /**
     * Сохранение продукта в заказе в таблицу товаров в заказе
     * @param $orderItem - продукт в заказе
     * @return mixed
     */
    protected function saveOrderItem($orderItem)
    {
        return App::call()->OrderItemRepository->save($orderItem);
    }

    /**
     * Сохранение биллинга в таблицу биллинга
     * @param $orderBilling - биллинг
     * @return mixed
     */
    protected function saveOrderBilling($orderBilling)
    {
        return App::call()->OrderBillingRepository->save($orderBilling);
    }

    /**
     * Сохранение адреса в таблицу адресов
     * @param $orderAddress - адрес
     * @return mixed
     */
    protected function saveOrderAddress($orderAddress)
    {
        return App::call()->OrderAddressRepository->save($orderAddress);
    }

    /**
     * Сохранение информации о заказе в таблицу информации о заказе
     * @param $orderInfo - информация о заказе
     * @return mixed
     */
    protected function saveOrderInfo($orderInfo)
    {
        return App::call()->OrderInfoRepository->save($orderInfo);
    }

    /**
     * Установка параметра в сессию
     * @param $name - имя параметра
     * @param $value - значение параметра
     * @return mixed
     */
    protected function sessionSet($name, $value)
    {
        return App::call()->Request->sessionSet($name, $value);
    }

    /**
     * Получение параметра из сессии
     * @param $name - имя параметра
     * @return mixed
     */
    protected function sessionGet($name)
    {
        return App::call()->Request->sessionGet($name);
    }

    /**
     * Удаление заказа из таблицы заказов
     * @param $id - id заказа
     * @return mixed
     */
    protected function deleteOrderFromDB($id)
    {
        return App::call()->OrderRepository->deleteOrder($id);
    }

    /**
     * Изменение статуса заказа
     * @param $id - id заказа
     * @param $status - статус
     * @return mixed
     */
    protected function changeOrderStatusInDB($id, $status)
    {
        return App::call()->OrderRepository->changeOrderStatus($id, $status);
    }
}