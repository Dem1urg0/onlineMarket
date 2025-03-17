<?php

namespace App\controllers;

use App\main\App;

class errorController extends Controller
{
    protected $defaultAction = 'error';
    public $code = '404';
    public $message = 'Not Found';

    public function run($action, $e = null)
    {
        if (!empty($e)){
            $this->code = $e->getCode();
            $this->message = $e->getMessage();
        }
        return parent::run($action);
    }

    public function errorAction()
    {
        return $this->render(
            'error',
            [
                'message' => $this->message,
                'code' => $this->code,
            ]);
    }
}