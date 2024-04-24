<?php

namespace App\Service;

use Carbon\Carbon;
use Elastic;
use Elasticsearch\ClientBuilder;
use Exception;
use Ramsey\Uuid\Uuid;


class TasksService
{


    /**
     * @throws Exception
     */
    public function get($id): array
    {
        $params = [
            "index" => "tasks-index",
            "id" => $id
        ];

        try {
            $task = ClientBuilder::create()->build()->get($params);
            return ["data" => $task];
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            throw new Exception("Task not found", 404);
        }

    }

    /**
     * @throws Exception
     */
    public function search($request): array
    {

        //Если оба поля пустые, возвращаем ошибку
        if (($request->has("subject") && $request->has("text"))) {
            throw new Exception("It is impossible to use both subject and text at the same time", 400);
        } elseif (!($request->has("subject") || $request->has("text"))) {
            throw new Exception("Either subject or text is required", 400);
        }


        $params = [
            "index" => "tasks-index",
            "body" => [
                "query" => [
                    "bool" => [
                        "must" => []
                    ]
                ]
            ]
        ];

        // Добавляем только одно условие в зависимости от того, какое поле было предоставлено
        if ($request->has("subject")) {
            $params["body"]["query"]["bool"]["must"][] = ["match" => ["subject" => $request["subject"]]];
        } elseif ($request->has("text")) {
            $params["body"]["query"]["bool"]["must"][] = ["match" => ["text" => $request["text"]]];
        }

        $tasks = ClientBuilder::create()->build()->search($params);
        return ["data" => $tasks["hits"]["hits"]];
    }

    /**
     * @throws Exception
     */
    public function create($request)
    {
        $column_names = ["subject", "text", "answer", "author_id"];

        $fieldsToCheck = $request->keys();
        foreach ($fieldsToCheck as $field) {
            if (!in_array($field, $column_names)) {
                throw new Exception("Field '{$field}' does not exist in the database table", 400);
            }
        }

        $uuid = Uuid::uuid4()->toString();
        $now = Carbon::now(new \DateTimeZone('UTC'));
        $formattedDate = $now->format('Y-m-d\TH:i:s.u\Z');


        $params = [
            'index' => 'tasks-index',
            'id'    => $uuid,
            'body'  => [
                'subject' => $request["subject"],
                'text' => $request['text'],
                'answer' => $request['answer'],
                'author_id' => $request['author_id'],
                'created_at' => $formattedDate
            ]
        ];

        $response = ClientBuilder::create()->build()->index($params);

        return $response;


    }

    /**
     * @throws Exception
     */
    public function delete($id): array
    {
        $params = [
            "index" => "tasks-index",
            "id" => $id
        ];

        try {
            ClientBuilder::create()->build()->delete($params);

        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {

        }
        return ["data" => null];
    }
}
