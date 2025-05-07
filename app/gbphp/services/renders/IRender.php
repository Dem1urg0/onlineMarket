<?php

namespace App\Services\renders;

/**
 * Интерфейс для рендеринга шаблонов
 */
interface IRender
{
    public function render($template, $params = []);

}