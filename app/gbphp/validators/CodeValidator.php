<?php

namespace App\validators;

use App\Exceptions\apiException;
use App\main\App;

/**
 * Валидатор промокода
 */
class CodeValidator extends Validator
{
    /**
     * Проверка промокода
     * @param $code - код
     * @param $country - страна
     * @param $isApi - флаг API
     * @return mixed
     * @throws apiException
     */
    public function validateCode($code,$country, $isApi = false)
    {
        $this->checkEmpty($code, $isApi);
        $this->checkEmpty($country, $isApi);

        if (!($sale = App::call()->CodeRepository->getSale($code, $country))) {
            throw new ApiException('Код не найден', 404);
        }
        return $sale;
    }
}