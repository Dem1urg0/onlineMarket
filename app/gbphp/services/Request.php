<?php

namespace App\services;

/**
 * Класс для работы с запросами
 */
class Request
{
    /**
     * @var string $actionName - название метода
     */
    protected $actionName;

    /**
     * @var string $controllerName - название контроллера
     */
    protected $controllerName;

    /**
     * @var string $requestString - строка запроса
     */
    protected $requestString;

    /**
     * @var $session - сессия
     */
    protected $session;

    /**
     * @var array $params - параметры запроса
     */
    protected $params;

    /**
     * @var bool $apiCheck - API флаг
     */
    protected $apiCheck = false;

    /**
     * @var bool $adminCheck - флаг администратора
     */
    protected $adminCheck = false;

    /**
     * Конструктор
     * Начинаем сессию.
     * Получаем строку запроса.
     * Получаем параметры GET/POST.
     * Инициализируем метод parseRequest.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->params = [
            'get' => $_GET,
            'post' => $_POST,
        ];
        $this->parseRequest();
    }

    /**
     * Метод для парсинга строки запроса и определения контроллера и метода
     * @return void
     */
    protected function parseRequest()
    {
        // Убираем начальный слеш и GET параметры из строки запроса
        $path = trim(parse_url($this->requestString, PHP_URL_PATH), '/');

        $pattern = "#^(?P<api>api/)?(?:(?P<admin>admin)(?:/(?P<controller>\w+)?)?|(?P<simplecontroller>\w+))(?:/(?P<action>\w+))?$#ui";

        if (preg_match($pattern, $path, $matches)) {
            if (!empty($matches['api'])) {
                $this->apiCheck = true;
            }
            if (!empty($matches['admin'])) {
                $this->adminCheck = true;
            }
            if ($this->adminCheck) {
                $this->controllerName = $matches['controller'] ?? null;
            } else {
                $this->controllerName = $matches['simplecontroller'] ?? null;
            }
            if (!empty($matches['action'])) {
                $this->actionName = $matches['action'];
            }
        }
    }

    /**
     * Метод для получения GET параметров
     * @param $params - параметры
     * @return array|mixed
     */
    public function get($params = '')
    {
        if (!$_SERVER['REQUEST_METHOD'] == 'GET') {
            return array();
        }
        if (empty($params)) {
            return $this->params['get'];
        }

        if (!empty($this->params['get'][$params])) {
            return $this->params['get'][$params];
        }
        return array();
    }

    /**
     * Метод для получения названия метода
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Метод для получения названия контроллера
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Метод для получения флага API
     * @return bool
     */
    public function isApi()
    {
        return $this->apiCheck;
    }

    /**
     * Метод для получения флага администратора
     * @return bool
     */
    public function adminCheck()
    {
        return $this->adminCheck;
    }

    /**
     * Метод для получения POST параметров
     * @param $params - параметры
     * @return array|mixed
     */
    public function post($params = '')
    {
        if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
            return array();
        }
        if (empty($params)) {
            return $this->params['post'];
        }
        if (!empty($this->params['post'][$params])) {
            return $this->params['post'][$params];
        }
        return array();
    }

    /**
     * Метод для установки параметра сессии
     * @param $key - ключ
     * @param $value - значение
     * @return void
     */
    public function sessionSet($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Метод для получения параметра сессии
     * @param $key - ключ
     * @return array|mixed
     */
    public function sessionGet($key)
    {
        return $_SESSION[$key] ?? [];
    }

    /**
     * Метод для удаления параметра сессии
     * @param $key - ключ
     * @return void
     */
    public function sessionDelete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Метод для добавления значения в массив сессии
     * @param $key - ключ массива
     * @param $value - добавляемое значение
     * @return void
     */
    public function sessionAddToArr($key, $value)
    {
        if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        $_SESSION[$key][] = $value;
    }

    /**
     * Метод для получения JSON данных из запроса
     * @return mixed
     */
    public function getJsonData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Метод для проверки типа запроса на POST
     * @return bool
     */
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        } else return false;
    }

    /**
     * Метод для проверки типа запроса на GET
     * @return bool
     */
    public function isGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        } else return false;
    }
}