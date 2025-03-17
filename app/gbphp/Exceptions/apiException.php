<?php
namespace App\Exceptions;

use Exception;

class apiException extends Exception
{
    private array $response;

    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);

        $this->response = [
                'code' => $code,
                'msg' => $message,
                'success' => false,
        ];
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}