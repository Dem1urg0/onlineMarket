<?php

namespace App\services\renders;

/**
 * Интерфейс для рендеринга шаблонов
 */
interface IRender
{
    public function render($template, $params = []);

}