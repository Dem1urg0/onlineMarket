<?php

namespace App\services;

/**
 * Класс для отправки ответа
 */
class Response
{
    /**
     * Метод отправляет ответ в формате JSON
     * @param $content - данные для отправки
     * @return never
     */
    public function sendJson($content): never
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }
}