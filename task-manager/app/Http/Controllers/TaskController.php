<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->listTasks($request->query('status'));
        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->all());
        return response()->json($task, 201);
    }

    public function show(string $id): JsonResponse
    {
        $task = $this->taskService->getTask($id);
        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->updateTask($task, $request->all());
        return response()->json($updatedTask);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);
        return response()->json(null, 204);
    }

    public function getTasksByStatus(Request $request)
    {

        $status = $request->query('status');
        $tasks = $this->taskService->getTasksByStatus($status); 
        return response()->json($tasks, 200);

    }

}
