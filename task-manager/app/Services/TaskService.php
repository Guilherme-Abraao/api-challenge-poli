<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskService
{
    private TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listTasks(?string $status = null)
    {
        return $this->repository->getAllTasks($status);
    }

    public function createTask(array $data): Task
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->createTask($data);
    }

    public function getTask(string $id): ?Task
    {
        return $this->repository->getTaskById($id);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $validator = Validator::make($data, [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->updateTask($task, $data);
    }

    public function deleteTask(Task $task): void
    {
        $this->repository->deleteTask($task);
    }
}
