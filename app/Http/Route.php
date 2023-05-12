<?php

namespace App\Http;

class Route
{
    public static array $routes = [];

    public static function get(string $url, callable|string $controller, string $action = null)
    {
        self::registerRoute('get', $url, $controller, $action);
    }

    public static function post(string $url, callable|string $controller, string $action = null)
    {
        self::registerRoute('post', $url, $controller, $action);
    }

    private static function registerRoute(string $method, string $url, callable|string $controller, string $action = null)
    {
        if ($controller instanceof \Closure) {
            self::$routes[$method][$url] = [
                'function' => $controller,
            ];
            return;
        }

        self::$routes[$method][$url] = [
            'controller' => $controller,
            'action' => $action,
        ];
    }
}