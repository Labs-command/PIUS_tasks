<?php


namespace App\Http\Controllers;

use App\Service\ReportedTasksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ReportedTaskController extends Controller
{
    private ReportedTasksService $reportedTasksService;


    public function __construct(ReportedTasksService $reportedTasksService)
    {
        $this->reportedTasksService = $reportedTasksService;
    }

    /**
     * @OA\Get(
     *    path="/tasks/api/v1/reported-tasks",
     *    summary="Поиск по параметрам",
     *    tags={"Reported Task"},
     *
     * @OA\RequestBody(
     * @OA\JsonContent(
     *            allOf={
     * @OA\Schema(
     * @OA\Property(property="title", type="string", example="Some title"),
     * @OA\Property(property="likes", type="integer", example=20),
     *                )
     *            }
     *        )
     *    ),
     *
     * @OA\Response(
     *        response=200,
     *        description="Ok",
     * @OA\JsonContent(
     * @OA\Property(property="data",  type="object",
     * @OA\Property(property="title", type="string", example="Some title"),
     * @OA\Property(property="likes", type="integer", example=20),
     *            ),
     *        ),
     *    ),
     * )
     */
    public function search(Request $request): JsonResponse //ресурc возвращать
    {
        try {
            $tasks = $this->reportedTasksService->search($request);

            return response()->json($tasks);

        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    /**
     * @param  $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        try {
            $tasks = $this->reportedTasksService->get($id);
            return response()->json($tasks);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    public function create(Request $request): JsonResponse
    {
        try {
            $tasks = $this->reportedTasksService->create($request);
            return response()->json($tasks);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }

    //    public function replace($id ,Request $request): JsonResponse
    //    {
    //        $result = $this->reportedTasksService->replace($id, $request);
    //
    //        return response()->json($result);
    //    }

    public function patch($id, Request $request): JsonResponse
    {
        try {
            $result = $this->reportedTasksService->patch($id, $request);
            return response()->json($result);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $result = $this->reportedTasksService->delete($id);
            return response()->json($result);

        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }


}
