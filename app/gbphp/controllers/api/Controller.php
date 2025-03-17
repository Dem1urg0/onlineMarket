<?php

namespace App\controllers\api;

use App\controllers\Controller as mainController;
use App\Exceptions\ApiException;
use App\main\App;
use App\services\renders\IRender;
use App\services\Request;
use Exception;

abstract class Controller extends mainController
{
    protected  $validator;
    protected $response;

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->response = App::call()->Response;
        $this->validator = App::call()->Validator;
    }

    public function run($action)
    {
        try {
            $this->validator->validatePost(true);
            return parent::run($action);
        } catch (ApiException $e) {
            $this->sendJson($e->getResponse());
            return true;
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'msg' => 'Server Error',
                'code' => 500,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
    }
    public function sendJson($data)
    {
        $this->response->sendJson($data);
    }
}