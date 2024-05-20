<?php

namespace App\Providers;

use App\Service\Task\TaskService;
use App\Service\Task\TaskServiceEloquent;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TaskService::class, TaskServiceEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
