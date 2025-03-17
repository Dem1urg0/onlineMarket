<?php

namespace App\controllers\api;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;

class codeController extends Controller
{
    protected $codeValidator;

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->codeValidator = App::call()->CodeValidator;
    }

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