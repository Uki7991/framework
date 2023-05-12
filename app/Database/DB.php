<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;

class DB
{
    private static $connection;

    private function __construct()
    {
    }

    public static function getConnection()
    {
        if (!self::$connection) {
            $connectionParams = [
                'dbname' => env('DB_DATABASE', ''),
                'user' => env('DB_USERNAME', ''),
                'password' => env('DB_PASSWORD', ''),
                'host' => env('DB_HOST', ''),
                'driver' => 'pdo_mysql',
            ];

            self::$connection = DriverManager::getConnection($connectionParams);
        }

        return self::$connection;
    }
}