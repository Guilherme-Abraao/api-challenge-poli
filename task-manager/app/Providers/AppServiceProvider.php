<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TaskRepository;
use App\Services\TaskService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TaskRepository::class);
        $this->app->singleton(TaskService::class);
    }

    public function boot()
    {
        //
    }
}
