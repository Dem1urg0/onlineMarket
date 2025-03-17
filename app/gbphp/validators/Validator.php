<?php

namespace App\validators;
use App\Exceptions\apiException;
use App\main\App;

class Validator
{
    public function validatePost($isApi = false): void
    {
        if (!App::call()->Request->isPost()) {
            $this->throwException('Метод не POST', 405, $isApi);
        }
    }

    public function validateGet($isApi = false): void
    {
        if (!App::call()->Request->isGet()) {
            $this->throwException('Метод не GET', 405, $isApi);
        }
    }

    public function validateJsonData($isApi = false): array
    {
        $data = App::call()->Request->getJsonData();
        if (empty($data)) {
            $this->throwException('Нет данных', 400, $isApi);
        }
        return $data;
    }

    public function throwException($msg, $code, $isApi = false)
    {
        if ($isApi) {
            throw new apiException($msg, $code);
        }
        throw new \Exception($msg, $code);
    }

    public function checkEmpty($data, $isApi = false)
    {
        if (empty($data)) {
            $this->throwException('Данные не найдены', 400, $isApi);
        }
    }
}