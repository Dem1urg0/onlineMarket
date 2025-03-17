<?php

namespace App\main;

use App\traits\TSingleton;

class App
{
    use TSingleton;

    public $config = [];
    private $components = [];

    static public function call(): App
    {
        return static::getInstance();
    }

    public function run(array $config)
    {
        $this->config = $config;
        $this->runController();
    }

    protected function runController()
    {
        $request = new \App\services\Request();

        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        new \Twig\Loader\FilesystemLoader();

        $isApi = $request->isApi();
        $isAdmin = $request->adminCheck();

        // Формируем префикс для неймспейса
        $namespacePrefix = match (true) {
            $isApi && $isAdmin => 'api\\admin\\',
            $isApi => 'api\\',
            $isAdmin => 'admin\\',
            default => ''
        };

        $fullControllerName = $namespacePrefix . ($controllerName ? strtolower($controllerName) : '');

        $controllerClass = 'App\\controllers\\' . $fullControllerName . 'Controller';

        try {
            if (!class_exists($controllerClass)) {
                throw new \Exception("$controllerClass not found", 404);
            }
            $controller = new $controllerClass(
                new \App\services\renders\TwigRender(),
                $request
            );
            echo $controller->run($actionName);
        } catch (\Exception $e) {
            $controller = new \App\controllers\errorController(
                new \App\services\renders\TwigRender(),
                $request
            );
            $actionName = 'error';
            echo $controller->run($actionName, $e);
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->components)) {
            return $this->components[$name];
        }
        if (!array_key_exists($name, $this->config['components'])) {
            return null;
        }
        $className = $this->config['components'][$name]['class'];
        if (array_key_exists('config', $this->config['components'][$name])) {
            $config = $this->config['components'][$name]['config'];
            $component = new $className($config);
        } else {
            $component = new $className();
        }
        $this->components[$name] = $component;
        return $component;
    }
}