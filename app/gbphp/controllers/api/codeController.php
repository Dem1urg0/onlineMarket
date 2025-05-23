<?php

namespace App\Controllers\api;

use App\main\App;
use App\Services\renders\IRender;
use App\Services\Request;
use App\validators\CodeValidator;

/**
 * Контроллер api промокодов
 */
class codeController extends controller
{
    /**
     * Валидатор кодов
     * @var CodeValidator|mixed|null $codeValidator
     */
    protected CodeValidator $codeValidator;

    /**
     * Конструктор контроллера
     * @param IRender $render - Экземпляр класса для render
     * @param Request $request - Экземпляр класса для request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->codeValidator = App::call()->CodeValidator;
    }

    /**
     * Проверка кода
     */
    public function checkCodeAction()
    {
        $request = $this->validator->validateJsonData(true);

        $sale = $this->codeValidator->validateCode($request['code'], $request['country'], true);

        $response = [
            'code' => 200,
            'message' => 'Код найден',
            'success' => true,
            'sale' => $sale
        ];

        $this->sendJson($response);
    }
}