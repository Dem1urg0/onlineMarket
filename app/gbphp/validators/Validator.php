<?php

namespace App\validators;
use App\main\App;
use App\traits\TthrowError;

/**
 * Основной класс валидации
 */
class Validator
{
    /**
     * Подключение трейта для вывода ошибок
     */
    use TthrowError;

    /**
     * Валидация метода POST
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function validatePost($isApi = false): void
    {
        if (!App::call()->Request->isPost()) {
            $this->throwException('Метод не POST', 405, $isApi);
        }
    }

    /**
     * Валидация метода GET
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function validateGet($isApi = false): void
    {
        if (!App::call()->Request->isGet()) {
            $this->throwException('Метод не GET', 405, $isApi);
        }
    }

    /**
     * Валидация JSON данных
     * @param $isApi - флаг API
     * @return array
     * @throws \App\Exceptions\apiException
     */
    public function validateJsonData($isApi = false): array
    {
        $data = App::call()->Request->getJsonData();
        if (empty($data)) {
            $this->throwException('Нет данных', 400, $isApi);
        }
        return $data;
    }

    /**
     * Проверка на пустоту
     * @param $data - данные
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function checkEmpty($data, $isApi = false)
    {
        if (empty($data)) {
            $this->throwException('Данные не найдены', 400, $isApi);
        }
    }

    /**
     * Проверка на числовое значение
     * @param $data - данные
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function checkNumeric($data, $isApi = false)
    {
        if (!is_numeric($data)) {
            $this->throwException('Данные не являются числом', 400, $isApi);
        }
    }
}