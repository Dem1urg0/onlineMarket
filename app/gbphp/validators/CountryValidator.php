<?php

namespace App\validators;

use App\main\App;

class CountryValidator extends Validator
{
    public function validateCountry($country, $isApi = false)
    {
        if (empty($country) || !$id_country = App::call()->CountryRepository->getIdCountryByName($country)) {
            $this->throwException('Страна не найдена', 400, $isApi);
        }
        return $id_country;
    }
}