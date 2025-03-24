<?php

namespace App\validators;

/**
 * Валидатор для пагинации
 */
class PaginationValidator extends Validator
{
    /**
     * @var array|string[] $sortTypes - возможные типы сортировки
     */
    protected array $sortTypes = ['price', 'name'];

    /**
     * Валидация параметров пагинации
     * @param $pageParams - параметры пагинации
     * @param $isApi - флаг API
     * @return true
     * @throws \App\Exceptions\apiException
     */
    public function validatePagination($pageParams, $isApi = false)
    {
        if (!is_numeric($pageParams['count']) || !is_numeric($pageParams['page'])) {
            $this->throwException('Не верные данные пагинации', 400, $isApi);
        }

        if ($pageParams['count'] < 1 || $pageParams['count'] > 15) {
            $this->throwException('Не верное количество элементов на странице', 400, $isApi);
        }

        if ($pageParams['page'] < 1) {
            $this->throwException('Не верный номер страницы', 400, $isApi);
        }

        return true;
    }

    /**
     * Проверка сортировки
     * @param $sort - тип сортировки
     * @param $isApi - флаг API
     * @return void
     * @throws \App\Exceptions\apiException
     */
    public function checkSort($sort, $isApi = false)
    {
        if (!empty($sort)) {
            if (!in_array($sort, $this->sortTypes)) {
                $this->throwException('Не верный тип сортировки', 400, $isApi);
            }
        }
    }
}