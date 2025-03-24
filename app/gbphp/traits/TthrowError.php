<?php

namespace App\traits;

use App\Exceptions\apiException;

/**
 * Трейт для выброса исключений
 */
trait TthrowError
{
    /**
     * Выброс исключения
     * @param $msg - сообщение
     * @param $code - код ошибки
     * @param $isApi - флаг, если true - выбросить исключение для API
     * @return mixed
     * @throws apiException
     */
    public function throwException($msg, $code, $isApi = false)
    {
        if ($isApi) {
            throw new apiException($msg, $code);
        }
        throw new \Exception($msg, $code);
    }
}