<?php


namespace App\Http\Controllers;

use App\Service\ReportedTasksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ReportedTaskController extends Controller
{
    private ReportedTasksService $reportedTasksService;


    public function __construct(ReportedTasksService $reportedTasksService)
    {
        $this->reportedTasksService = $reportedTasksService;
    }

    /**
     * @OA\Post(
     *     path="/tasks/api/v1/reported-tasks/search",
     *     operationId="searchReportedTasks",
     *     tags={"Reported Task"},
     *     summary="Search for reported tasks",
     *     description="Search through the reported tasks with optional sorting and pagination.if the search field is empty, then all entries are displayed!!!",
     * @OA\Parameter(
     *         name="search_field",
     *         in="query",
     *         description="Field name to search by",
     *         required=false,
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="search_value",
     *         in="query",
     *         description="Value of the field to search for",
     *         required=false,
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="sort_field",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         description="Order to sort by (asc or desc)",
     *         required=false,
     * @OA\Schema(
     *             type="string",
     *             default="asc",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     * @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset where to start pagination",
     *         required=false,
     * @OA\Schema(
     *             type="integer",
     *             default=0
     *         )
     *     ),
     * @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit how many tasks to retrieve",
     *         required=false,
     * @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="Successful operation",
     * @OA\JsonContent(
     *              type="object",
     * @OA\Property(
     *                  property="data",
     *                  type="array",
     * @OA\Items(
     *                      type="object",
     *                      properties={
     * @OA\Property(property="task_id",        type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",        type="string", example="laudantium"),
     * @OA\Property(property="text",           type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",         type="string", example="nam"),
     * @OA\Property(property="reason_comment", type="string", example="laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur"),
     * @OA\Property(property="author_id",      type="string", format="uuid", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     * @OA\Property(property="updated_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z")
     *                      }
     *                  ),
     *                  example={
     *                      {
     *                          "task_id": "2e8ad285-436c-3387-b4e8-31080551cdc2",
     *                          "subject": "laudantium",
     *                          "text": "Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet.",
     *                          "answer": "nam",
     *                          "reason_comment": "laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur",
     *                          "author_id": "cabf0ff5-4e77-34c7-9ab2-64745907cf1a",
     *                          "created_at": "2024-04-14T16:45:53.000000Z",
     *                          "updated_at": "2024-04-14T16:45:53.000000Z"
     *                      },
     *                      {
     *                          "task_id": "2ef86cd6-ed9c-3d61-9a65-2f891a1ed813",
     *                          "subject": "ullam",
     *                          "text": "Eos aut quia quas autem eveniet. Voluptas delectus cupiditate enim enim veritatis et. Similique tempora fuga itaque debitis voluptate ut omnis.",
     *                          "answer": "odio",
     *                          "reason_comment": "illum sed omnis qui tempora consequatur et facere voluptatem cupiditate",
     *                          "author_id": "4b84c22a-dc49-3aad-9693-c4ebc6af4e91",
     *                          "created_at": "2024-04-14T16:45:53.000000Z",
     *                          "updated_at": "2024-04-14T16:45:53.000000Z"
     *                      }
     *                  }
     *              ),
     * @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  properties={
     * @OA\Property(property="search_field",   type="string", example="subject"),
     * @OA\Property(property="search_value",   type="string", example="explicabo"),
     * @OA\Property(property="sort_order",     type="string", example="asc"),
     * @OA\Property(property="offset",         type="integer", example=0),
     * @OA\Property(property="limit",          type="integer", example=10)
     *                  }
     *              )
     *          )
     *      ),
     * @OA\Response(
     *         response=400,
     *         description="Invalid search or sort field",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",         type="string", example="Invalid search field")
     *         ),
     *
     *     ),
     * @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     * @OA\JsonContent(
     *               type="object",
     * @OA\Property(property="errors",         type="string", example="An unexpected error occurred")
     *           )
     *       )
     * )
     */
    public function search(Request $request): \App\Http\Resources\ReportedTaskResourceCollection|JsonResponse
    {
        try {

            return $this->reportedTasksService->search($request);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    /**
     * @OA\Get(
     *    path="/tasks/api/v1/reported-tasks/{id}",
     *    summary="Retrieve a reported task by UUID",
     *    tags={"Reported Task"},
     * @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        description="The UUID of the reported task to retrieve",
     * @OA\Schema(
     *            type="string",
     *            format="uuid",
     *            example="2e8ad285-436c-3387-b4e8-31080551cdc2"
     *        )
     *    ),
     * @OA\Response(
     *        response=200,
     *        description="Successful retrieval of the reported task",
     * @OA\JsonContent(
     *            type="object",
     * @OA\Property(property="data",           type="object",
     * @OA\Property(property="task_id",        type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",        type="string", example="laudantium"),
     * @OA\Property(property="text",           type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",         type="string", example="nam"),
     * @OA\Property(property="reason_comment", type="string", example="laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur"),
     * @OA\Property(property="author_id",      type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     * @OA\Property(property="updated_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z")
     *            )
     *        )
     *    ),
     * @OA\Response(
     *          response=400,
     *          description="Invalid UUID format",
     * @OA\JsonContent(
     *              type="object",
     * @OA\Property(property="errors",         type="string", example="Invalid UUID format")
     *          )
     *      ),
     * @OA\Response(
     *        response=404,
     *        description="Task not found",
     * @OA\JsonContent(
     *            type="object",
     * @OA\Property(property="errors",         type="string", example="Task not found")
     *        )
     *    ),
     * @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     * @OA\JsonContent(
     *              type="object",
     * @OA\Property(property="errors",         type="string", example="An unexpected error occurred")
     *          )
     *      )
     * )
     */
    public function get($id): \App\Http\Resources\ReportedTaskResource|JsonResponse
    {
        try {
            return $this->reportedTasksService->get($id);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }

    /**
     * @OA\Post(
     *     path="/tasks/api/v1/reported-tasks",
     *     summary="Create a new reported task",
     *     tags={"Reported Task"},
     * @OA\RequestBody(
     *         required=true,
     *         description="Data for creating a new reported task",
     * @OA\JsonContent(
     *             required={"subject", "text", "answer", "reason_comment", "author_id"},
     * @OA\Property(property="subject",        type="string", example="laudantium"),
     * @OA\Property(property="text",           type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",         type="string", example="nam"),
     * @OA\Property(property="reason_comment", type="string", example="laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur"),
     * @OA\Property(property="author_id",      type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="data",           type="object",
     * @OA\Property(property="task_id",        type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",        type="string", example="laudantium"),
     * @OA\Property(property="text",           type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",         type="string", example="nam"),
     * @OA\Property(property="reason_comment", type="string", example="laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur"),
     * @OA\Property(property="author_id",      type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     * @OA\Property(property="updated_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z")
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=400,
     *         description="Validation error",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",         type="string", example="Missing required fields: subject, text")
     *         )
     *     ),
     * @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     * @OA\JsonContent(
     *               type="object",
     * @OA\Property(property="errors",         type="string", example="An unexpected error occurred")
     *           )
     *     )
     * )
     */
    public function create(Request $request):\App\Http\Resources\ReportedTaskResource|JsonResponse
    {
        try {
            return $this->reportedTasksService->create($request);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * @OA\Patch(
     *     path="/tasks/api/v1/reported-tasks/{id}",
     *     summary="Update specific fields of a reported task",
     *     tags={"Reported Task"},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the task to update",
     * @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     * @OA\RequestBody(
     *         required=true,
     *         description="Partial data of the reported task to update",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="text",           type="string", example="Updated detailed description of the task."),
     * @OA\Property(property="answer",         type="string", example="Updated initial answer to the task."),
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="data",           type="object",
     * @OA\Property(property="task_id",        type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",        type="string", example="Initilal Subject"),
     * @OA\Property(property="text",           type="string", example="Updated detailed description of the task."),
     * @OA\Property(property="answer",         type="string", example="Updated answer to the task."),
     * @OA\Property(property="reason_comment", type="string", example="Initial reason for creating the task."),
     * @OA\Property(property="author_id",      type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     * @OA\Property(property="updated_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z")
     *             )
     *         )
     *
     *     ),
     * @OA\Response(
     *         response=400,
     *         description="Invalid input, object invalid",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",         type="string", example="Field 'xyz' does not exist in the database table")
     *         )
     *     ),
     * @OA\Response(
     *         response=404,
     *         description="Task not found",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",         type="string", example="Task not found")
     *         )
     *     ),
     * @OA\Response(
     *         response=500,
     *         description="Internal server error",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",         type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */
    public function patch($id, Request $request):\App\Http\Resources\ReportedTaskResource|JsonResponse
    {
        try {
            return $this->reportedTasksService->patch($id, $request);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }
    }
    /**
     * @OA\Delete(
     *     path="/tasks/api/v1/reported-tasks/{id}",
     *     summary="Delete a reported task by UUID",
     *     tags={"Reported Task"},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the reported task to delete",
     *         required=true,
     * @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Task successfully deleted",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function delete($id): JsonResponse
    {
        try {
            return $this->reportedTasksService->delete($id);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }


}
