<?php

namespace App\controllers\admin;

use App\main\App;

class usersController extends controller
{
    protected $defaultAction = 'all';

    public function allAction()
    {
        $renderCount = 5;
        $userCountRender = 1;

        $maxOrdersCount = App::call()->OrderRepository->getMaxOrdersCount();

        if (($countOfUsers = App::call()->UserRepository->getCountOfAll()) > $userCountRender) {

            $params = ['page' => 1, 'count' => $renderCount,];
            $data = ['min' => 0, 'max' => $maxOrdersCount];

            $users = App::call()->UserRepository->getWithFilter($params, $data);
            $renderType = 'default';
        } else {
            $users = App::call()->UserRepository->getAllUsersInfo();
            $renderType = 'many';
        }
        $pagesCount = ceil($countOfUsers / $renderCount);

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