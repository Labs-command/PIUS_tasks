<?php

namespace App\Service;

use App\Models\ReportedTask;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ReportedTasksService
{
    public function search($request): Collection|array
    {
        $query = ReportedTask::query();

        if ($request->has('search_field') && $request->has('search_value')) {
            echo $request->search_field;
            if(Schema::hasColumn('reported_tasks', $request->search_field)) {
                $query->where($request->input('search_field'), 'like', '%' . $request->input('search_value') . '%');
            } else {
                return ['error'=>400, 'message'=>"This search_field does not exist in the database table"];
            }
        }

        $sortOrder = $request->has('sort_order') ? $request->input('sort_order') : "asc";
        if ($request->has('sort_field')) {
            $sortField = $request->input('sort_field');
            $query->orderBy($sortField, $sortOrder);
        }

        $offset = $request->has('offset') ? intval($request->input('offset')) : 0;
        $limit = $request->has('limit') ? intval($request->input('limit')) : 10;
        $query->offset($offset)->limit($limit);

        $response = ['data'=>$query->get(), 'meta'=>['search_field'=>$request->search_field,
            'search_value'=>$request->search_value,
            'sort_order'=>$sortOrder,
            'offset'=>$offset,
            'limit'=>$limit]];

        return $response;
    }

    public function get($id)
    {
        $task = ReportedTask::find($id);

        if (!$task) {
            return ['error'=>404, 'message'=>"Task not found"];
        }

        return ['data'=>$task];
    }

    public function create($request)
    {
        try{
            $task = ReportedTask::create(
                [
                    'subject' => $request->subject,
                    'text' => $request->text,
                    'answer' => $request->answer,
                    'reason_comment' => $request->reason_comment,
                    'author_id' => $request->author_id,
                ]
            );
            return ['data'=>$task];
        }catch (\Exception $e){
            Log::channel('errorlog')->error($e->getMessage());
            return ['code' => 500, 'message' => "User update error"];
        }

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
    public function patch($taskId, $request)
    {
        try{
            $task = ReportedTask::find($taskId);

            if (!$task) {
                return ['error'=>404, 'message'=>"Task not found"];
            }


            $task->update($request->except('id'));

            $task->save();

            return $task;
        }catch (\Exception $e){
            Log::channel('errorlog')->error($e->getMessage());
            return ['code' => 500, 'message' => "User update error"];
        }
    }

    public function delete($id)
    {
        try{
            ReportedTask::destroy($id);
            return ['data'=>null];
        }catch (\Exception $e){
            Log::channel('errorlog')->error($e->getMessage());
            return ['code' => 500, 'message' => "User update error"];
        }
    }

}
