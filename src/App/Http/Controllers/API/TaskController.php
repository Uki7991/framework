<?php

namespace Src\App\Http\Controllers\API;

use Src\App\Http\Controllers\Controller;
use Src\App\Models\Task;
use Src\App\Services\TaskService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    public function index(Request $request)
    {
        return new JsonResponse(Task::all());
    }

    public function store(Request $request)
    {
        $result = $this->taskService->store($request->request->get('name'), $request->files->get('photo'));

        return new JsonResponse($result);
    }

    public function show(Request $request, string $id)
    {
        return $this->taskService->get($id);
    }

    public function showWithQuery(Request $request)
    {
        return $this->taskService->get($request->query->get('task_id'));
    }
}