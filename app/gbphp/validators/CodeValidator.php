<?php

namespace App\validators;

use App\Exceptions\apiException;
use App\main\App;
use App\validators\Validator;

class CodeValidator extends Validator
{
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