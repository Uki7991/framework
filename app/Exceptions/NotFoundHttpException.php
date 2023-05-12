<?php

namespace App\Exceptions;

use Throwable;

class NotFoundHttpException extends \Exception
{
    public function __construct(string $message = "", string $url = "", int $code = 404, ?Throwable $previous = null)
    {
        $message = $url . " " . $message;
        parent::__construct($message, $code, $previous);
    }
}