<?php
/**
 * Инициализация приложения
 */
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
$config = include dirname(__DIR__) . '/main/config.php';
\App\main\App::call()->run($config);
