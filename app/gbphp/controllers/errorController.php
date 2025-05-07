<?php

namespace App\Controllers;

/**
 * Контроллер ошибок
 *
 * Контроллер для обработки ошибок приложения.
 *
 * @package App\controllers
 */
class errorController extends Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'error';

    /**
     * Код ошибки
     * @var string
     */
    public $code = '404';

    /**
     * Сообщение об ошибке
     * @var string
     */
    public string $message = 'Not Found';

    /**
     * Обработка ошибок
     *
     * @param string $action - Действие
     * @param \Exception|null $e - Объект ошибки
     * @return mixed
     */
    public function run($action, $e = null)
    {
        if (!empty($e)){
            $this->code = $e->getCode();
            $this->message = $e->getMessage();
        }
        return parent::run($action);
    }

    /**
     * Рендеринг страницы ошибки
     * @return string
     */
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