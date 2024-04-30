<?php

namespace App\Service;

use App\Http\Resources\ReportedTaskResource;
use App\Http\Resources\ReportedTaskResourceCollection;
use App\Models\ReportedTask;
use Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class ReportedTasksService
{
    /**
     * @throws Exception
     */
    public function search($request): ReportedTaskResourceCollection
    {
        $query = ReportedTask::query();

        if ($request->has('search_value')) {
            $query->where('subject', 'like', '%' . $request->input('search_value') . '%');
        }

        $sortOrder = $request->has('sort_order') ? $request->input('sort_order') : "asc";
        if ($request->has('sort_field') && $request->input('sort_field')) {
            $sortField = $request->input('sort_field');
            if ($sortOrder == "asc" || $sortOrder == "desc") {
                $query->orderBy($sortField, $sortOrder);
            } else {
                throw new Exception("Invalid sort field", 400);
            }
        }

        $offset = $request->has('offset') ? intval($request->input('offset')) : 0;
        $limit = $request->has('limit') ? intval($request->input('limit')) : 10;
        $query->offset($offset)->limit($limit);

        $tasks = $query->get();
        return new ReportedTaskResourceCollection($tasks, compact("offset", "limit"));
    }

    /**
     * @throws Exception
     */
    public function get($id): ReportedTaskResource
    {
        $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
        if (!preg_match($pattern, $id)) {
            throw new Exception("Invalid uuid", 400);
        }

        try {
            $task = ReportedTask::findOrFail($id);
            return new ReportedTaskResource($task);
        } catch (Exception $e) {
            throw new Exception("Task not found", 404);
        }
    }

    /**
     * @throws Exception
     */
    public function confirm($id): bool
    {

        $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
        if (!preg_match($pattern, $id)) {
            throw new Exception("Invalid uuid", 400);
        }

        $task = ReportedTask::findOrFail($id);
        $uuid = Uuid::uuid4()->toString();

        $params = [
            'index' => 'tasks-index',
            'id' => $uuid,
            'body' => [
                'subject' => $task->subject,
                'text' => $task->text,
                'answer' => $task->answer,
                'author_id' => $task->author_id,
                'created_at' => $task->created_at,
            ]
        ];

        $response = ClientBuilder::create()->build()->index($params);
        if(array_key_exists('error', $response) && $response['error']) {
            throw new Exception($response["error"]);
        }

        $this::delete($id);

        return true;
    }

    /**
     * @throws Exception
     */
    public function create($request): ReportedTaskResource
    {
        $fieldsToCheck = $request->keys();

        foreach ($fieldsToCheck as $field) {
            if (!Schema::hasColumn('reported_tasks', $field)) {
                throw new Exception("Field '{$field}' does not exist in the database table", 400);
            }
        }

        $missingFields = array_diff(["subject", "text", "answer", "reason_comment", "author_id"], $request->keys());
        if (count($missingFields) > 0) {
            throw new \Exception("Missing required fields: " . implode(', ', $missingFields), 400);
        }

        $task = ReportedTask::create(
            [
                'subject' => $request->subject,
                'text' => $request->text,
                'answer' => $request->answer,
                'reason_comment' => $request->reason_comment,
                'author_id' => $request->author_id,
            ]
        );
        return new ReportedTaskResource($task);

    }

    public function patch($taskId, $request): ReportedTaskResource
    {
        try {
            $task = ReportedTask::find($taskId);
            if (!$task) {
                throw new Exception("Task not found", 404);
            }
        } catch (Exception $e) {
            throw new Exception("Invalid uuid", 400);
        }

        $fieldsToCheck = $request->keys();

        foreach ($fieldsToCheck as $field) {
            if (!Schema::hasColumn('reported_tasks', $field)) {
                throw new Exception("Field '{$field}' does not exist in the database table", 400);
            }
        }

        $task->update($request->except('id'));
        return new ReportedTaskResource($task);
    }

    /**
     * @throws Exception
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
        if (!preg_match($pattern, $id)) {
            throw new Exception("Invalid uuid", 400);
        }
        ReportedTask::destroy($id);
        return response()->json(["data" => null]);

    }

}
