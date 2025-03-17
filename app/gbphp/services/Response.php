<?php

namespace App\services;

class Response
{
    public function sendJson($content): never
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }
}