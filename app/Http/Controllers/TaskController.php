<?php

namespace App\Http\Controllers;

use App\Service\TasksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private TasksService $tasksService;


    public function __construct(TasksService $TasksService)
    {
        $this->tasksService = $TasksService;
    }

    public function get($id): JsonResponse
    {
        try {
            $tasks = $this->tasksService->get($id);
            return response()->json($tasks);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    public function search(Request $request): JsonResponse //ресурc возвращать
    {
        try {
            $tasks = $this->tasksService->search($request);
            return response()->json($tasks);

        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    public function create(Request $request): JsonResponse
    {
        try {
            $tasks = $this->tasksService->create($request);
            return response()->json($tasks);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $result = $this->tasksService->delete($id);
            return response()->json($result);

        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }
}
