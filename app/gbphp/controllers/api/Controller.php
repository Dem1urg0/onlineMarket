<?php

namespace App\Controllers\api;

use App\Controllers\Controller as mainController;
use App\Exceptions\ApiException;
use App\main\App;
use App\Services\renders\IRender;
use App\Services\Request;
use App\Services\Response;
use App\validators\Validator;
use Exception;

/**
 * Базовый абстрактный контроллер API
 */
abstract class controller extends mainController
{
    /**
     * Валидатор
     * @var Validator
     */
    protected Validator $validator;
    /**
     * Класс для работы с ответом
     * @var Response|mixed|null $response
     */
    protected Response $response;

    /**
     * Конструктор контроллера
     * @param IRender $render - Экземпляр класса для render
     * @param Request $request - Экземпляр класса для request
     */
    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->response = App::call()->Response;
        $this->validator = App::call()->Validator;
    }

    /**
     * Предварительная проверка прав доступа, а также запуск действия
     * Так же обработка исключений
     * @param $action - действие
     * @return mixed
     * @throws \Exception
     */
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
    /**
     * Отправка ответа в формате JSON
     * @param $data - данные
     */
    public function sendJson($data)
    {
        $this->response->sendJson($data);
    }
}