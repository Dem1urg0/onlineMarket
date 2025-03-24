<?php

namespace App\validators;

use App\main\App;

/**
 * Валидатор страны
 */
class CountryValidator extends Validator
{
    /**
     * Проверка страны
     * @param $country - название страны
     * @param $isApi - флаг API
     * @return mixed
     * @throws \App\Exceptions\apiException
     */
    public function validateCountry($country, $isApi = false)
    {
        if (empty($country) || !$id_country = App::call()->CountryRepository->getIdCountryByName($country)) {
            $this->throwException('Страна не найдена', 400, $isApi);
        }
        return $id_country;
    }
}