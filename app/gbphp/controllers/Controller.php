<?php

namespace App\Controllers;

use App\main\App;
use App\Services\renders\IRender;
use App\Services\Request;
use App\validators\Validator;

/**
 * Базовый абстрактный контроллер приложения
 *
 * Определяет основные методы и функциональность для всех контроллеров системы.
 * Реализует базовую логику маршрутизации, рендеринга и доступа к запросам.
 *
 * @package App\controllers
 */
abstract class Controller
{
    /**
     * Действие по умолчанию
     * @var string
     */
    protected string $defaultAction = 'all';
    /**
     * Сервис рендеринга
     * @var IRender
     */
    protected IRender $render;
    /**
     * Сервис запросов
     * @var Request
     */
    protected Request $request;
    /**
     * Валидатор
     * @var Validator
     */
    protected Validator $validator;
    /**
     * Сервис шаблонизатора твиг
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * Конструктор контроллера
     *
     * @param IRender $render - Экземпляр сервиса render
     * @param Request $request - Экземпляр сервиса request
     */
    public function __construct(IRender $render, Request $request)
    {
        $this->render = $render;
        $this->request = $request;
        $this->validator = App::call()->Validator;
    }

    /**
     * Предварительная проверка действия и запуск метода действия
     *
     * @param string|null $action Название действия для выполнения
     * @return mixed
     * @throws \Exception Если метод действия не существует
     */
    public function run($action)
    {
        if (empty($action)) {
            $action = $this->defaultAction;
        }
        $method = $action . 'Action';
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        throw new \Exception("Метод не найден", 404);
    }

    /**
     * Рендеринг шаблона
     *
     * @param string $template Путь к шаблону
     * @param array $params Параметры для передачи в шаблон
     * @return string Сгенерированный шаблон
     */
    public function render($template, $params){
        return $this->render->render($template,$params);
    }

    /**
     * Получение параметров GET запроса
     * @param $params - параметры запроса
     * @return array|mixed
     */
    public function getRequest($params = [])
    {
        return $this->request->get($params);
    }
    /**
     * Получение параметров POST запроса
     * @param $params - параметры запроса
     * @return array|mixed
     */
    public function postRequest($params = []){
        return $this->request->post($params);
    }
    /**
     * Получение параметров SESSION
     * @param $params - параметры запроса
     * @return array|mixed
     */
    public function getSession($params = []){
        return $this->request->sessionGet($params);
    }

    /**
     * Удаление параметра из SESSION
     * @param $key - ключ параметра
     * @return void
     */
    public function deleteFromSession($key)
    {
        $this->request->sessionDelete($key);
    }
}