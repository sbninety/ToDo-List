<?php

namespace App\Service\Task;

use App\Models\Task;
use App\Repositories\Task\TaskRepository;
use Illuminate\Support\Collection;

interface TaskService
{
    public function getRepository(): TaskRepository;

    public function getAll(): Collection;

    public function store(array $attributes): ?Task;

    public function destroy(int $id): bool;

    public function update(array $attributes, int $id): mixed;

    public function restore(int $id): bool;
}
