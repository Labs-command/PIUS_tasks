<?php

namespace App\Service;

use App\Models\ReportedTask;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class ReportedTasksService
{
    /**
     * @throws Exception
     */
    public function search($request): Collection|array
    {
        $query = ReportedTask::query();

        if ($request->has('search_field') && $request->has('search_value')) {

            if (Schema::hasColumn('reported_tasks', $request->search_field)) {
                $query->where($request->input('search_field'), 'like', '%' . $request->input('search_value') . '%');
            } else {
                throw new Exception("Invalid search field", 400);
            }
        }

        $sortOrder = $request->has('sort_order') ? $request->input('sort_order') : "asc";
        if ($request->has('sort_field')) {
            $sortField = $request->input('sort_field');
            if ($sortField == "asc" || $sortField == "desc") {
                $query->orderBy($sortField, $sortOrder);
            } else {
                throw new Exception("Invalid sort field", 400);
            }
        }

        $offset = $request->has('offset') ? intval($request->input('offset')) : 0;
        $limit = $request->has('limit') ? intval($request->input('limit')) : 10;
        $query->offset($offset)->limit($limit);

        $response = ['data' => $query->get(), 'meta' => ['search_field' => $request->search_field,
            'search_value' => $request->search_value,
            'sort_order' => $sortOrder,
            'offset' => $offset,
            'limit' => $limit]];

        return $response;
    }

    /**
     * @throws Exception
     */
    public function get($id)
    {

        try {
            $task = ReportedTask::find($id);
            if (!$task) {
                throw new Exception("Task not found", 404);
            }
            return ['data' => $task];
        } catch (Exception $e) {
            throw new Exception("Invalid uuid", 400);
        }
    }

    /**
     * @throws Exception
     */
    public function create($request): array
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
        return ['data' => $task];

    }

    //    public function replace($taskId, $request)
    //    {
    //        try{
    //            $task = ReportedTask::find($taskId);
    //
    //            if (!$task) {
    //                return ['error'=>404, 'message'=>"Task not found"];
    //            }
    //
    //
    //            $task->update([
    //                'subject' => $request->subject,
    //                'text' => $request->input('text', 'test'),
    //                'answer' => $request->input('answer', 'test'),
    //                'reason_comment' => $request->input('reason_comment', null),
    //                'author_id' => $request->input('author_id', 'e63a652c-b216-3656-a05f-e0bcd7a772b1'),
    //            ]);
    //
    //            $task->save();
    //
    //            return $task;
    //        }catch (\Exception $e){
    //            Log::channel('errorlog')->error($e->getMessage());
    //            return ['code' => 500, 'message' => "User update error"];
    //        }
    //
    //    }
    public function patch($taskId, $request): array
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
        return ['data' => $task];
    }

    public function delete($id)
    {
        ReportedTask::destroy($id);
        return ['data' => null];

    }

}
