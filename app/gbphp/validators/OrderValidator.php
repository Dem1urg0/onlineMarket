<?php

namespace App\validators;

use App\main\App;

class OrderValidator extends Validator
{
    public function validateOrderAccess($userId, $orderId, $status, $isApi = false)
    {
        $this->checkOrderId($orderId);

        $ownerId = App::call()->OrderRepository->getOwnerOrder($orderId);

        if (($userId == $ownerId && $status == 'created') || !App::call()->RoleMiddleware->checkAdmin()) {
            return true;
        }

        $this->throwException('Нет доступа', 403, $isApi);

    }

    public function checkOrderId($orderId)
    {
        if (empty(App::call()->OrderRepository->getOne($orderId))) {
            $this->throwException('Заказ не найден', 404);
        }
    }

    public function checkStatus($status1, $status2, $isApi = false)
    {
        if ($status1 == $status2) {
            $this->throwException('Статус не изменен', 400, $isApi);
        }
    }
    public function normalizeOrdersCount($min, $max, $isApi = false)
    {
        $maxOrders =  App::call()->OrderRepository->getMaxOrdersCount();

        $max = ($max == 'max' || $max > $maxOrders) ? $maxOrders : $max;
        $min = ($min > $maxOrders) ? $maxOrders : $min;

        if ($min > $max) {
            list($min, $max) = [$max, $min];
        }
        return ['min' => $min, 'max' => $max];
    }
}