<?php

namespace App\Middleware;
use App\traits\TthrowError;

/**
 * Основной абстрактный класс Middleware
 */
abstract class Middleware
{
    // Подключаем трейт для выброса ошибок
    use TthrowError;
}