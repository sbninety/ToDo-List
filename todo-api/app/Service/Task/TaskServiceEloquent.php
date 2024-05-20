<?php

namespace App\Service\Task;

use App\Models\Task;
use App\Repositories\Task\TaskRepository;
use Illuminate\Support\Collection;

class TaskServiceEloquent implements TaskService
{
    public function __construct(protected TaskRepository $taskRepository)
    {
    }

    public function getRepository(): TaskRepository
    {
        return $this->taskRepository;
    }

    public function getAll(): Collection
    {
        return $this->taskRepository->all();
    }

    public function store(array $attributes): ?Task
    {
        return $this->taskRepository->create($attributes);
    }

    public function destroy(int $id): bool
    {
        $task = $this->taskRepository->withTrashed()->where('id', $id)->first();
        if ($task->trashed()) {
            return $task->forceDelete();
        }
        return $this->taskRepository->delete($id);
    }

    public function update(array $attributes, int $id): mixed
    {
        return $this->taskRepository->update($attributes, $id);
    }

    public function restore(int $id): bool
    {
        $task = $this->taskRepository->withTrashed()->where('id', $id)->first();
        return $task->restore();
    }
}
