<?php
namespace App\Exceptions;

use Exception;

/**
 * Класс исключений для API
 */
class apiException extends Exception
{
    /**
     * Массив ответа исключения
     * @var array
     */
    private array $response;

    /**
     * Конструктор класса
     * @param string $message Сообщение исключения
     * @param int $code Код исключения
     */
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);

        $this->response = [
                'code' => $code,
                'msg' => $message,
                'success' => false,
        ];
    }

    /**
     * Получение ответа исключения
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}