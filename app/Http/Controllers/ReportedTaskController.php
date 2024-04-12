<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReportedTask;
use App\Service\ReportedTasksService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use SebastianBergmann\Diff\Exception;

class ReportedTaskController extends Controller
{
    private ReportedTasksService $reportedTasksService;


    public function __construct(ReportedTasksService $reportedTasksService)
    {
        $this->reportedTasksService = $reportedTasksService;
    }


    public function search(Request $request): JsonResponse
    {
        try{
            $tasks = $this->reportedTasksService->search($request);

            return response()->json($tasks);

        } catch(\Exception $e){
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    /**
     * @OA\Get(
     *     path="/examples",
     *     operationId="examplesAll",
     *     tags={"Examples"},
     *     summary="Display a listing of the resource",
     *     security={
     *       {"api_key": {}},
     *     },
     * @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     * @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     * @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     * @OA\MediaType(
     *             mediaType="application/json",
     * @OA\Schema()
     *         )
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        try{
            $tasks = $this->reportedTasksService->get($id);
            return response()->json($tasks);
        }catch(\Exception $e){
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    public function create(Request $request):JsonResponse
    {
        try{
            $tasks = $this->reportedTasksService->create($request);
            return response()->json($tasks);
        }catch(\Exception $e){
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

    public function patch($id ,Request $request): JsonResponse
    {
        try{
            $result = $this->reportedTasksService->patch($id, $request);
            return response()->json($result);
        }catch(\Throwable $e){
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }

    public function delete($id): JsonResponse
    {
        try{
            $result = $this->reportedTasksService->delete($id);
            return response()->json($result);

        }catch(\Exception $e){
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }



}
