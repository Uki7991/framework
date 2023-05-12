<?php

namespace App\Console;

use App\Exceptions\Handler as ExceptionHandler;

class Kernel
{
    private $handler;

    public function __construct()
    {
        $this->handler = new ExceptionHandler();
    }

    /**
     * @param array $input
     * @return int
     */
    public function handle(array $input): int
    {
        $status = 0;
        try {
            array_shift($input);
            $this->callCommand(array_shift($input), $input);
        } catch (\Throwable $exception) {
            $this->handler->renderException($exception);
            $status = 1;
        }

        return $status;
    }

    private function callCommand($command, $params)
    {
        list($service, $function) = explode(':', $command);

        $service()->{$function}();
    }
}