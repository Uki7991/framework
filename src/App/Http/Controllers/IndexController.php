<?php

namespace Src\App\Http\Controllers;

use Src\App\Services\TaskService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    public function handle(Request $request)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $result = $this->taskService->store($request->request->get('name'), $request->files->get('photo'));

            return new JsonResponse($result);
        } elseif ($request->isMethod(Request::METHOD_GET)) {
            return $this->taskService->get($request->query->get('task_id'));
        }

        return new JsonResponse([
            'status' => 'not_found',
            'result' => null
        ], 404);
    }
}