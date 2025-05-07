<?php

namespace App\Controllers\admin;

use App\main\App;

/**
 * Контроллер для работы с заказами администрации
 */
class ordersController extends controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'all';

    /**
     * Вывод всех заказов пользователей
     * @return string
     */
    public function allAction()
    {
        $order = App::call()->OrderService->getOrders([]);

        return $this->render('/admin/orders',
            [
                'orders' => $order,
            ]);
    }
}