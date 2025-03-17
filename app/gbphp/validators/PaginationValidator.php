<?php

namespace App\validators;

class PaginationValidator extends Validator
{
    protected $sortTypes = ['price', 'name'];

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

    public function checkSort($sort, $isApi = false)
    {
        if (!empty($sort)) {
            if (!in_array($sort, $this->sortTypes)) {
                $this->throwException('Не верный тип сортировки', 400, $isApi);
            }
        }
    }
}