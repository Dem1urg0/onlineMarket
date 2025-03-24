<?php

namespace App\validators;

use App\main\App;

/**
 * Валидатор заказов
 */
class OrderValidator extends Validator
{
    /**
     * Валидация прав доступа к заказу
     * @param $userId - id пользователя
     * @param $orderId - id заказа
     * @param $status - статус заказа
     * @param $isApi - флаг API
     * @return true|void
     * @throws \App\Exceptions\apiException
     */
    public function validateOrderAccess($userId, $orderId, $status, $isApi = false)
    {
        $this->checkOrderId($orderId);

        $ownerId = App::call()->OrderRepository->getOwnerOrder($orderId);

        if (($userId == $ownerId && $status == 'created') || !App::call()->RoleMiddleware->checkAdmin($isApi)) {
            return true;
        }

        $this->throwException('Нет доступа', 403, $isApi);

    }

    /**
     * Валидация id заказа
     * @param $orderId - id заказа
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function checkOrderId($orderId)
    {
        if (empty(App::call()->OrderRepository->getOne($orderId))) {
            $this->throwException('Заказ не найден', 404);
        }
    }

    /**
     * Валидация статуса заказа
     * @param $status1 - устанавливаемый статус заказа
     * @param $status2 - старый статус заказа
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function checkStatus($status1, $status2, $isApi = false)
    {
        if ($status1 == $status2) {
            $this->throwException('Статус не изменен', 400, $isApi);
        }
    }

    /**
     * Нормализация количества заказов
     * @param $min - минимальное количество заказов
     * @param $max - максимальное количество заказов
     * @param $isApi - флаг API
     * @return array
     */
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