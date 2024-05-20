<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\EditTaskRequest;
use App\Http\Resources\TaskResource;
use App\Service\Task\TaskService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index()
    {
        try {
            $data = $this->taskService->getAll();
            return response()->json(TaskResource::collection($data), 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function store(CreateTaskRequest $request)
    {
        try {
            $task = $this->taskService->store($request->validated());
            return response()->json(new TaskResource($task), 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->taskService->destroy($id);
            return response()->json('Successfully', 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function update(EditTaskRequest $request, int $id)
    {
        try {
            $this->taskService->update($request->validated(), $id);
            return response()->json('Successfully', 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function restore(int $id)
    {
        try {
            $this->taskService->restore($id);
            return response()->json('Successfully', 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
