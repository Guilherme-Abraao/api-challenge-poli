<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Validation\ValidationException;
use Mockery;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    protected TaskService $taskService;
    protected $taskRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepositoryMock = Mockery::mock(TaskRepository::class);
        
        $this->taskService = new TaskService($this->taskRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateTaskSuccess()
    {
        $data = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
            'status' => 'pending',
        ];

        $task = new Task($data);

        $this->taskRepositoryMock
            ->shouldReceive('createTask')
            ->once()
            ->with($data)
            ->andReturn($task);

        $result = $this->taskService->createTask($data);

        $this->assertEquals('Nova Tarefa', $result->title);
    }


    public function testUpdateTaskSuccess()
    {
        $task = new Task([
            'id' => '15c8113f-35a6-45ce-bbaa-cfcde26ab88b',
            'title' => 'Tarefa Original',
            'status' => 'pending',
        ]);

        $data = ['title' => 'Tarefa Atualizada', 'status' => 'completed'];

        $updatedTask = new Task(array_merge($task->toArray(), $data));

        $this->taskRepositoryMock
            ->shouldReceive('updateTask')
            ->once()
            ->with($data)
            ->andReturn($updatedTask);

        $result = $this->taskService->updateTask($task, $data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Tarefa Atualizada', $result->title);
        $this->assertEquals('completed', $result->status);
    }

    public function testDeleteTaskSuccess()
    {
        $task = new Task(['id' => '15c8113f-35a6-45ce-bbaa-cfcde26ab88b']);

        $this->taskRepositoryMock
            ->shouldReceive('deleteTask')
            ->once()
            ->with($task);

        $this->taskService->deleteTask($task);

        $this->assertTrue(true); 
    }

    public function testDeleteNewTaskSuccess()
    {
        $task = new Task();
        $this->taskRepositoryMock->shouldReceive('deleteTask')->with($task)->once();

        $this->taskService->deleteTask($task);

        $this->assertTrue(true); 
    }
    
    public function testListTasksSuccess()
    {
        $tasks = [new Task(), new Task()];
        $this->taskRepositoryMock->shouldReceive('getAllTasks')->once()->andReturn($tasks);

        $result = $this->taskService->listTasks();

        $this->assertEquals($tasks, $result);
    }

    
    public function testListTasksWithStatus()
    {
        $tasks = [new Task(), new Task()];
        $this->taskRepositoryMock->shouldReceive('getAllTasks')->with('pending')->once()->andReturn($tasks);

        $result = $this->taskService->listTasks('pending');

        $this->assertEquals($tasks, $result);
    }

    public function testListNewTasksSuccess()
    {
        $tasks = [
            new Task(['id' => '1', 'title' => 'Tarefa 1', 'description' => 'Tarefa 1', 'status' => 'pending']),
            new Task(['id' => '2', 'title' => 'Tarefa 2', 'description' => 'Tarefa 2', 'status' => 'completed']),
        ];

        $this->taskRepositoryMock
            ->shouldReceive('getAllTasks')
            ->once()
            ->andReturn($tasks);

        $result = $this->taskService->listTasks();

        $this->assertCount(2, $result);
        $this->assertEquals('Tarefa 1', $result[0]->title);
    }


    public function testGetTaskSuccess()
    {
        $task = new Task();
        $this->taskRepositoryMock->shouldReceive('getTaskById')->with('1')->once()->andReturn($task);

        $result = $this->taskService->getTask('1');

        $this->assertEquals($task, $result);
    }


    
    public function testGetTasksByStatusThrowsExceptionWhenStatusIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->taskService->getTasksByStatus('');
    }

    public function testGetTaskSuccessId()
    {
        $task = new Task([
            'id' => '15c8113f-35a6-45ce-bbaa-cfcde26ab88b',
            'title' => 'Tarefa 1',
            'status' => 'pending',
        ]);

        $this->taskRepositoryMock
            ->shouldReceive('getTaskById')
            ->once()
            ->with('15c8113f-35a6-45ce-bbaa-cfcde26ab88b')
            ->andReturn($task);

        $result = $this->taskService->getTask('15c8113f-35a6-45ce-bbaa-cfcde26ab88b');

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Tarefa 1', $result->title);
    }
    
}
