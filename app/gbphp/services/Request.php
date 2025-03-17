<?php

namespace App\services;

class Request
{
    protected $actionName;
    protected $controllerName;
    protected $requestString;
    protected $session;
    protected $params;
    protected $apiCheck = false;
    protected $adminCheck = false;

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

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function isApi()
    {
        return $this->apiCheck;
    }

    public function adminCheck()
    {
        return $this->adminCheck;
    }

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

    public function sessionSet($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function sessionGet($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else return array();
    }

    public function sessionDelete($key)
    {
        unset($_SESSION[$key]);
    }

    public function sessionAddToArr($key, $value)
    {
        if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        $_SESSION[$key][] = $value;
    }

    public function getJsonData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        } else return false;
    }

    public function isGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        } else return false;
    }
}