<?php

namespace App\tests;

use App\Entities\Order\Order;
use App\Entities\Order\OrderItem;
use App\services\OrderService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    /**
     * @param $cart
     * @param $userData
     * @param $expected
     * @return void
     * @dataProvider dataForTestOrderFill
     */
    public function testOrderFill($cart, $userData, $expected)
    {
        $mockOrderService = $this->getMockBuilder(OrderService::class)
            ->onlyMethods(['getOrderObj', 'getOrderItemObj', 'saveOrderItem', 'sessionSet', 'saveOrder'])
            ->getMock();

        $mockOrder = $this->createMock(Order::class);
        $mockOrderItem = $this->createMock(OrderItem::class);

        $mockOrderService->method('getOrderObj')->willReturn($mockOrder);
        $mockOrderService->method('getOrderItemObj')->willReturn($mockOrderItem);

        $result = $mockOrderService->orderFill($cart, $userData);
        $this->assertEquals($result, $expected);

    }

    public function dataForTestOrderFill()
    {
        return [
            [
                'cart' => [],
                'userData' => [],
                'expected' => [
                    'msg' => 'Пустые значения',
                    'success' => false,
                ]
            ],
            [
                'cart' => [
                    ['id' => 1, 'count' => 1],
                    ['id' => 2, 'count' => 2],
                ],
                'userData' => [],
                'expected' => [
                    'msg' => 'Пустые значения',
                    'success' => false,
                ]
            ],
            [
                'cart' => [],
                'userData' => [
                    'id' => 1,
                    'address' => 'Address 1',
                ],
                'expected' => [
                    'msg' => 'Пустые значения',
                    'success' => false,
                ]
            ],
            [
                'cart' => [
                    ['id' => 1, 'count' => 1],
                    ['id' => 2, 'count' => 2],
                ],
                'userData' => [
                    'id' => 1,
                    'address' => 'Address 1',
                ],
                'expected' => [
                    'msg' => 'Заказ создан',
                    'success' => true,
                ]
            ],
        ];
    }


    /**
     * @param $ordersData
     * @param $expected
     * @return void
     * @dataProvider dataForTestSortProductsInOrders
     */
    public function testSortProductsInOrders($ordersData, $expected)
    {
        $orders = [];
        foreach ($ordersData as $orderData) {
            $mockOrder = $this->createMock(Order::class);
            foreach ($orderData as $key => $value) {
                $mockOrder->$key = $value;
            }
            $orders[] = $mockOrder;
        }
        $orderService = new OrderService();
        $result = $orderService->sortProductsInOrders($orders);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestSortProductsInOrders()
    {
        return [
            'case_1' => [
                'orderData' => [
                    [
                        'id' => 1,
                        'user_id' => 101,
                        'date' => '2024-12-01',
                        'address' => 'Address 1',
                        'status' => 'Delivered',
                        'name' => 'Product A',
                        'price' => 10.99,
                        'count' => 2,
                    ],
                    [
                        'id' => 1,
                        'user_id' => 101,
                        'date' => '2024-12-01',
                        'address' => 'Address 1',
                        'status' => 'Delivered',
                        'name' => 'Product B',
                        'price' => 15.49,
                        'count' => 1,
                    ],
                    [
                        'id' => 2,
                        'user_id' => 102,
                        'date' => '2024-12-02',
                        'address' => 'Address 2',
                        'status' => 'Pending',
                        'name' => 'Product C',
                        'price' => 20.00,
                        'count' => 1,
                    ],
                ],
                'expected' => [
                    1 => [
                        'info' => [
                            'user_id' => 101,
                            'date' => '2024-12-01',
                            'address' => 'Address 1',
                            'status' => 'Delivered',
                        ],
                        'products' => [
                            ['name' => 'Product A', 'price' => 10.99, 'count' => 2],
                            ['name' => 'Product B', 'price' => 15.49, 'count' => 1],
                        ],
                    ],
                    2 => [
                        'info' => [
                            'user_id' => 102,
                            'date' => '2024-12-02',
                            'address' => 'Address 2',
                            'status' => 'Pending',
                        ],
                        'products' => [
                            ['name' => 'Product C', 'price' => 20.00, 'count' => 1],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $params
     * @param $expected
     * @return void
     * @dataProvider dataForTestDeleteOrder
     */
    public function testDeleteOrder($params, $expected)
    {
        $mockOrderService = $this->getMockBuilder(OrderService::class)
            ->onlyMethods(['deleteOrderFromDB', 'adminCheck', 'sessionGet', 'getOrderById'])
            ->getMock();

        $mockOrderService->method('adminCheck')->willReturn(true);
        $mockOrderService->method('sessionGet')->willReturn($params['user_id']);
        $mockOrderService->method('getOrderById')->willReturn('order');

        $result = $mockOrderService->deleteOrder($params);
        $this->assertEquals($expected, $result);
    }

    public function dataForTestDeleteOrder()
    {
        return [
            [
                [
                    'user_id' => 1,
                    'order_id' => 1
                ],
                [
                    'success' => true,
                    'msg' => 'Заказ удален'
                ]
            ],
            [
                [
                    'user_id' => 1,
                    'order_id' => null
                ],
                [
                    'success' => false,
                    'msg' => 'Нет данных'
                ]
            ],
            [
                [
                    'user_id' => null,
                    'order_id' =>
                        1
                ],
                [
                    'success' => false,
                    'msg' => 'Нет данных'
                ]
            ],
            [
                [
                    'user_id' => null,
                    'order_id' => null
                ],
                [
                    'success' => false,
                    'msg' => 'Нет данных'
                ]
            ],
        ];
    }

    /**
     * @param $order_id
     * @param $status
     * @param $expected
     * @return void
     * @dataProvider dataForTestChangeOrderStatus
     */
    public function testChangeOrderStatus($order_id, $status, $expected)
    {
        $mockOrderService = $this->getMockBuilder(OrderService::class)
            ->onlyMethods(['getOrderById', 'changeOrderStatusInDB'])->getMock();

        $mockOrderService->method('getOrderById')->willReturn('order');
        $result = $mockOrderService->changeOrderStatus(['order_id' => $order_id, 'status' => $status]);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestChangeOrderStatus()
    {
        return [
            [1, 'created', ['success' => true, 'msg' => 'Статус изменен']],
            [1, null, ['success' => false, 'msg' => 'Нет данных']],
            [null, 'created', ['success' => false, 'msg' => 'Нет данных']],
            [null, null, ['success' => false, 'msg' => 'Нет данных']],
            [1, 'a', ['success' => false, 'msg' => 'Не вернные данные']],
        ];
    }

    /**
     * @param $order_id
     * @param $status
     * @param $expected
     * @return void
     * @throws \ReflectionException
     * @dataProvider dataForTestChangeHasError
     */
    public function testChangeHasError($order_id, $status, $expected)
    {
        $reflectionOrderService = new \ReflectionClass(OrderService::class);
        $reflectionChangeHasError = $reflectionOrderService->getMethod('ChangeHasError');
        $reflectionChangeHasError->setAccessible(true);

        $result = $reflectionChangeHasError->invoke(new OrderService(), ['status' => $status, 'order_id' => $order_id]);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestChangeHasError()
    {
        return [
            [1, 1, false],
            [1, null, true],
            [null, 1, true],
            [null, null, true],
        ];
    }

    /**
     * @param $user_id
     * @param $order_id
     * @param $expected
     * @return void
     * @throws \ReflectionException
     * @dataProvider dataForTestDeleteHasError
     */
    public function testDeleteHasError($user_id, $order_id, $expected)
    {
        $reflectionOrderService = new \ReflectionClass(OrderService::class);
        $reflectionDeleteHasError = $reflectionOrderService->getMethod('deleteHasError');
        $reflectionDeleteHasError->setAccessible(true);

        $result = $reflectionDeleteHasError->invoke(new OrderService(), ['user_id' => $user_id, 'order_id' => $order_id]);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestDeleteHasError()
    {
        return [
            [1, 1, false],
            [1, null, true],
            [null, 1, true],
            [null, null, true],
        ];
    }
}