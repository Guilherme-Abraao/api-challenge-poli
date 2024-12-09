<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_index_returns_empty_when_no_tasks()
    {
        $response = $this->getJson('/api/tasks');
 
        $response->assertOk()->assertJsonCount(0);
    }
 
    public function test_index_returns_filtered_tasks_by_status()
    {
        Task::factory()->state(['status' => 'pending'])->count(3)->create();
        Task::factory()->state(['status' => 'complete'])->count(2)->create();

        $response = $this->getJson('/api/tasks?status=pending');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn ($task) =>
                    $task->where('status', 'pending')
                )
            );
    }

    public function test_store_creates_task_successfully()
    {
        $payload = ['name' => 'Test Task', 'status' => 'pending'];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('name', 'Test Task')
                     ->where('status', 'pending')
                     ->etc()
            );

        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    public function test_store_fails_with_invalid_data()
    {
        $payload = ['name' => '', 'status' => 'invalid-status'];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'status']);
    }

    public function test_store_handles_large_payloads_gracefully()
    {
        $payload = ['name' => str_repeat('a', 256), 'status' => 'pending'];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_show_returns_task_by_id()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $task->id)
                     ->where('name', $task->name)
                     ->where('status', $task->status)
                     ->etc()
            );
    }

    public function test_show_returns_404_for_invalid_id()
    {
        $response = $this->getJson('/api/tasks/999');

        $response->assertNotFound();
    }

    public function test_show_validates_id_format()
    {
        $response = $this->getJson('/api/tasks/invalid-id');

        $response->assertNotFound();
    }

    public function test_update_updates_task_successfully()
    {
        $task = Task::factory()->create();

        $payload = ['name' => 'Updated Task', 'status' => 'complete'];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('name', 'Updated Task')
                     ->where('status', 'complete')
                     ->etc()
            );

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'Updated Task']);
    }
 

 }