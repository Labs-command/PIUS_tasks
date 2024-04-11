<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReportedTask;
use App\Service\ReportedTasksService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ReportedTaskController extends Controller
{
    private ReportedTasksService $reportedTasksService;


    public function __construct(ReportedTasksService $reportedTasksService)
    {
        $this->reportedTasksService = $reportedTasksService;
    }


    public function search(Request $request): JsonResponse
    {
        $tasks = $this->reportedTasksService->search($request);

        return response()->json($tasks);

    }

    public function get($id): JsonResponse
    {
        $tasks = $this->reportedTasksService->get($id);

        return response()->json($tasks);
    }

    public function create(Request $request):JsonResponse
    {
        $tasks = $this->reportedTasksService->create($request);

        return response()->json($tasks);
    }

    //    public function replace($id ,Request $request): JsonResponse
    //    {
    //        $result = $this->reportedTasksService->replace($id, $request);
    //
    //        return response()->json($result);
    //    }

    public function patch($id ,Request $request)
    {
        $result = $this->reportedTasksService->patch($id, $request);

        return response()->json($result);
    }

    public function delete($id): JsonResponse
    {
        $result = $this->reportedTasksService->delete($id);

        return response()->json($result);
    }



}
