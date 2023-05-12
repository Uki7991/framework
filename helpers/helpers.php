<?php

use App\Database\DB;
use App\Queue\Queue;

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key];

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('db')) {
    function db()
    {
        return DB::getConnection();
    }
}

if (!function_exists('queue')) {
    function queue()
    {
        return Queue::getInstance();
    }
}
