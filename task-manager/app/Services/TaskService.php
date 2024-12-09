<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\TaskNotFoundException;
use Exception;

class TaskService
{
    private TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listTasks(?string $status = null)
    {
        try {
            return $this->repository->getAllTasks($status);
        } catch (Exception $e) {
            throw new Exception("Erro ao consultar tasks: " . $e->getMessage());
        }
    }

    public function createTask(array $data): Task
    {
        try {
            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|in:pending,in_progress,completed',
            ], [
                'title.required' => 'O campo título é obrigatório.',
                'title.string' => 'O campo título deve ser uma string.',
                'title.max' => 'O campo título não pode ter mais de 255 caracteres.',
                'description.string' => 'O campo descrição deve ser uma string.',
                'status.in' => 'O campo status é inválido.',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            return $this->repository->createTask($data);
        } catch (ValidationException $e) {
            throw new ValidationException($e->validator);
        } catch (Exception $e) {
            throw new Exception("Erro ao criar a tarefa: " . $e->getMessage());
        }
    }

    public function getTask(string $id): Task
    {
        try {
            $task = $this->repository->getTaskById($id);

            return $task;
        } catch (TaskNotFoundException $e) {
        
            Log::error('Task not found', ['exception' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
    
            Log::error('Erro ao recuperar a tarefa', ['exception' => $e->getMessage()]);
            throw new Exception("Erro ao recuperar a tarefa: " . $e->getMessage());
        }
    }

public function updateTask(Task $task, array $data): Task
    {
        try {
            $validator = Validator::make($data, [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|in:pending,in_progress,completed',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            return $this->repository->updateTask($task, $data);
        } catch (ValidationException $e) {
            throw new ValidationException($e->validator);
        } catch (Exception $e) {
            throw new Exception("Error updating task: " . $e->getMessage());
        }
    }

    public function deleteTask(Task $task): void
    {
        try {
            $this->repository->deleteTask($task);
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar a tarefa: " . $e->getMessage());
        }
    }

    public function getTasksByStatus(string $status): Collection
    {
        if (empty($status)) {
            throw new \InvalidArgumentException('O status não pode ser vazio.');
        }

        return $this->taskRepository->getTasksByStatus($status);
    }
    
}
