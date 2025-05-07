<?php

namespace App\Controllers;

use App\main\App;
use App\Repositories\CountryRepository;
use App\Repositories\Order\OrderRepository;
use App\Services\OrderService;
use App\Services\renders\IRender;
use App\Services\Request;

/**
 * Контроллер заказов
 *
 * Определяет методы для работы с заказами в системе.
 *
 * @package App\controllers
 */
class orderController extends Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'all';
    /**
     * Сервис заказов
     * @var OrderService
     */
    protected OrderService $orderService;
    /**
     * Репозиторий заказов
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;
    /**
     * Репозиторий стран
     * @var CountryRepository
     */

    /**
     * Конструктор контроллера
     *
     * @param IRender $render - Экземпляр класса Render
     * @param Request $request - Экземпляр класса Request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->orderService = App::call()->OrderService;
        $this->orderRepository = App::call()->OrderRepository;
    }

    /**
     * Рендер страницы checkOut
     * @return string
     */
    public function checkoutAction()
    {
        return $this->render('checkout', []);
    }

    /**
     * Рендер страницы заказов пользователя в системе
     * @return string
     */
    public function allAction()
    {
        if (empty($id = App::call()->AuthMiddleware->isAuth())) {
            header('Location: /auth/');
        }

        $orders = $this->orderService->getOrders(['id' => $id]);

        return $this->render('orders',
            [
                'orders' => $orders,
            ]);
    }
}