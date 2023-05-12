<?php

namespace App\Http;

use App\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    private Request $request;
    private ExceptionHandler $handler;

    public function __construct()
    {
        $this->handler = new ExceptionHandler();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $this->request = $request;

        try {
            $response = $this->sendRequestThroughRoutes();
        } catch (\Throwable $exception) {
            $response = $this->handler->renderException($exception);
        }

        return $response;
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    private function sendRequestThroughRoutes(): Response
    {
        $routeData = $this->getControllerFromRoute();

        if (!$routeData) {
            throw new NotFoundHttpException('Route not found', $this->request->getRequestUri(), 404);
        }

        if (isset($routeData['function'])) {
            return $routeData['function']($this->request, ...$routeData['params']);
        }

        $controller = new $routeData['controller'];

        return $controller->{$routeData['action']}($this->request, ...$routeData['params']);
    }

    /**
     * @return array|null
     */
    private function getControllerFromRoute(): array|null
    {
        $routes = Route::$routes;
        $routeData = null;

        $requestUri = $this->request->getRequestUri();

        $requestUri = preg_replace('/#|\?.+/', '', $requestUri);

        foreach ($routes[mb_strtolower($this->request->getMethod())] as $url => $item) {
            if (preg_match('/^'.str_replace('/', '\/', $url).'$/i', $requestUri, $matches)) {
                $routeData = [
                    ...$item,
                    'params' => [
                        ...array_slice($matches, 1),
                    ],
                ];
                break;
            }
        }

        return $routeData;
    }
}