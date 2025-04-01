<?php

namespace App\controllers\admin;

use App\main\App;
use App\Repositories\Order\OrderRepository;
use App\Repositories\UserRepository;
use App\services\renders\IRender;
use App\services\Request;

/**
 * Класс контроллера работы с пользователями для администратора
 */
class usersController extends controller
{
    /**
     * Действие по умолчанию
     * @var string $defaultAction
     */
    protected string $defaultAction = 'all';
    protected OrderRepository $orderRepository;
    protected UserRepository $userRepository;

    /**
     * Конструктор контроллера
     * @param IRender $render
     * @param Request $request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->orderRepository = App::call()->OrderRepository;
        $this->userRepository = App::call()->UserRepository;
    }

    /**
     * Получение списка пользователей с фильтрацией и пагинацией
     * @return string
     */
    public function allAction()
    {
        $renderCount = 5;
        $userCountRender = 1;

        $maxOrdersCount = $this->orderRepository->getMaxOrdersCount();
        $countOfAllUsers = $this->userRepository->getCountOfAll();

        if ($countOfAllUsers > $userCountRender) {

            $params = ['page' => 1, 'count' => $renderCount,];
            $data = ['min' => 0, 'max' => $maxOrdersCount];

            $users = $this->userRepository->getWithFilter($params, $data);
            $renderType = 'default';
        } else {
            $users = $this->userRepository->getAllUsersInfo();
            $renderType = 'many';
        }
        $pagesCount = ceil($countOfAllUsers / $renderCount);

        return $this->render(
            '/admin/users',
            [
                'users' => $users,
                'maxOrdersCount' => $maxOrdersCount,
                'pagesCount' => $pagesCount,
                'renderPageType' => $renderType,
            ]);
    }
}