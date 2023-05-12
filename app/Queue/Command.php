<?php

namespace App\Queue;

#[\AllowDynamicProperties]
abstract class Command
{
    public function complete()
    {
        queue()->completeCommand($this->id);
    }

    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }
}