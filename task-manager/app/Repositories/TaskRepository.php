<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAllTasks(?string $status = null)
    {
        $query = Task::query();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function getTaskById(string $id): ?Task
    {
        return Task::find($id);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }

    public function getTasksByStatus(string $status): Collection
    {
        return Task::where('status', $status)->get(); 
    }
}
