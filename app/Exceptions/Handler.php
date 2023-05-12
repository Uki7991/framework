<?php

namespace App\Exceptions;

use App\Contracts\Exceptions\HttpExceptionContract;
use Symfony\Component\HttpFoundation\JsonResponse;

class Handler
{
    public function __construct()
    {
        @set_exception_handler([$this, 'handle']);
        @set_error_handler([$this, 'handleError']);
    }

    public function handle(\Throwable $exception)
    {
        return $this->renderException($exception);
    }

    public function handleError(int $level, string $message, string $fileName, string $line, array $trace = null)
    {
        $message = $message . "\n In ".$fileName." line ".$line;
        throw new \Exception($message, $level);
    }

    public function renderException(\Throwable $e)
    {
        return new JsonResponse($e->getMessage(), $this->isHttpException($e) ? $e->getCode() : 500, [], JSON_PRETTY_PRINT);
    }

    private function isHttpException(\Throwable $e)
    {
        return $e instanceof HttpExceptionContract;
    }
}