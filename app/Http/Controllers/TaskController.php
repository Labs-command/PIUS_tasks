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
    /**
     * @OA\Get(
     *    path="/tasks/api/v1/tasks/{id}",
     *    summary="Retrieve a task by UUID",
     *    tags={"Task"},
     * @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        description="The UUID of the task to retrieve",
     * @OA\Schema(
     *            type="string",
     *            format="uuid",
     *            example="2e8ad285-436c-3387-b4e8-31080551cdc2"
     *        )
     *    ),
     * @OA\Response(
     *        response=200,
     *        description="Successful retrieval of the task",
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
    /**
     * @OA\Get(
     *     path="/tasks/api/v1/tasks/search",
     *     tags={"Task"},
     *     summary="Search tasks in Elasticsearch",
     *     description="Search through the tasks based on various filters such as text, subject, and author ID with pagination.",
     * @OA\Parameter(
     *         name="search_query",
     *         in="query",
     *         required=false,
     *         description="Query string to search across multiple fields.",
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         required=false,
     *         description="Filter results by author ID.",
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="subject",
     *         in="query",
     *         required=false,
     *         description="Filter results by subject.",
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="text",
     *         in="query",
     *         required=false,
     *         description="Filter results by text content.",
     * @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         required=false,
     *         description="Pagination offset.",
     * @OA\Schema(type="integer",              default=0)
     *     ),
     * @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Limit the number of results returned.",
     * @OA\Schema(type="integer",              default=10)
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Successful search operation",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(
     *                 property="data",
     *                 type="array",
     * @OA\Items(
     *                       type="object",
     *                       properties={
     * @OA\Property(property="task_id",        type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",        type="string", example="laudantium"),
     * @OA\Property(property="text",           type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",         type="string", example="nam"),
     * @OA\Property(property="reason_comment", type="string", example="laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur"),
     * @OA\Property(property="author_id",      type="string", format="uuid", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     * @OA\Property(property="updated_at",     type="string", format="date-time", example="2024-04-14T16:45:53.000000Z")
     *                       }
     *                   ),
     *                   example={
     *                       {
     *                           "task_id": "2e8ad285-436c-3387-b4e8-31080551cdc2",
     *                           "subject": "laudantium",
     *                           "text": "Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet.",
     *                           "answer": "nam",
     *                           "reason_comment": "laudantium suscipit aut praesentium ut itaque enim qui rerum consequuntur",
     *                           "author_id": "cabf0ff5-4e77-34c7-9ab2-64745907cf1a",
     *                           "created_at": "2024-04-14T16:45:53.000000Z",
     *                           "updated_at": "2024-04-14T16:45:53.000000Z"
     *                       },
     *                       {
     *                           "task_id": "2ef86cd6-ed9c-3d61-9a65-2f891a1ed813",
     *                           "subject": "ullam",
     *                           "text": "Eos aut quia quas autem eveniet. Voluptas delectus cupiditate enim enim veritatis et. Similique tempora fuga itaque debitis voluptate ut omnis.",
     *                           "answer": "odio",
     *                           "reason_comment": "illum sed omnis qui tempora consequatur et facere voluptatem cupiditate",
     *                           "author_id": "4b84c22a-dc49-3aad-9693-c4ebc6af4e91",
     *                           "created_at": "2024-04-14T16:45:53.000000Z",
     *                           "updated_at": "2024-04-14T16:45:53.000000Z"
     *                       }
     *                   }
     *               ),
     * @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 properties={
     * @OA\Property(property="offset",         type="integer", description="Offset of the current result set."),
     * @OA\Property(property="limit",          type="integer", description="Number of items per page.")
     *                 }
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=400,
     *         description="Invalid input or bad request",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="message",        type="string", example="Invalid search field")
     *         )
     *     )
     * @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     * @OA\JsonContent(
     *                type="object",
     * @OA\Property(property="errors",         type="string", example="An unexpected error occurred")
     *            )
     *      )
     *  )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/tasks/api/v1/tasks",
     *     summary="Create a new task",
     *     tags={"Task"},
     * @OA\RequestBody(
     *         required=true,
     *         description="Data for creating a new task",
     * @OA\JsonContent(
     *             required={"subject", "text", "answer", "reason_comment", "author_id"},
     * @OA\Property(property="subject",    type="string", example="laudantium"),
     * @OA\Property(property="text",       type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",     type="string", example="nam"),
     * @OA\Property(property="author_id",  type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="data",       type="object",
     * @OA\Property(property="task_id",    type="string", example="2e8ad285-436c-3387-b4e8-31080551cdc2"),
     * @OA\Property(property="subject",    type="string", example="laudantium"),
     * @OA\Property(property="text",       type="string", example="Optio porro est amet dolore. Voluptatibus qui deserunt unde veritatis cum maiores eveniet."),
     * @OA\Property(property="answer",     type="string", example="nam"),
     * @OA\Property(property="author_id",  type="string", example="cabf0ff5-4e77-34c7-9ab2-64745907cf1a"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-14T16:45:53.000000Z"),
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=400,
     *         description="Validation error",
     * @OA\JsonContent(
     *             type="object",
     * @OA\Property(property="errors",     type="string", example="Missing required fields: subject, text")
     *         )
     *     ),
     * @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     * @OA\JsonContent(
     *               type="object",
     * @OA\Property(property="errors",     type="string", example="An unexpected error occurred")
     *           )
     *     )
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/tasks/api/v1/tasks/{id}",
     *     summary="Delete a task by UUID",
     *     tags={"Task"},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the task to delete",
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
            $result = $this->tasksService->delete($id);
            return response()->json($result);

        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            return response()->json(['errors' => $e->getMessage()], $statusCode);
        }

    }
}
