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
        $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
        if(!preg_match($pattern, $id)) {
            throw new Exception("Invalid uuid", 400);
        }
        $params = [
            "index" => "tasks-index",
            "id" => $id
        ];

        try {
            $task = ClientBuilder::create()->build()->get($params);
            $taskId = $task['_id'];
            $taskBody = $task['_source'];

            $task = [
                "id" => $taskId,
            ];

            $task = array_merge($task, $taskBody);
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

        $offset = $request->has('offset') ? intval($request->input('offset')) : 0;
        $limit = $request->has('limit') ? intval($request->input('limit')) : 10;

        global $params;
        $must = [];
        if ($request->filled('search_query')) {
            $params = [
                'index' => 'tasks-index',
                'body' => [
                    "from" => $offset,
                    "size" => $limit,
                    'query' => [
                        'bool' => [
                            'should' => [
                                [
                                    "multi_match" =>[
                                        'query' => $request->input('search_query'),
                                        'fields' => ['subject', 'text', 'answer']
                                    ]
                                ],
                                [
                                    "wildcard" =>[
                                        'subject' => sprintf('*%s*', $request->input('search_query'))
                                    ]
                                ],
                                [
                                    "wildcard" =>[
                                        'text' => sprintf('*%s*', $request->input('search_query'))
                                    ]
                                ],
                                [
                                    "wildcard" =>[
                                        'answer' => sprintf('*%s*', $request->input('search_query'))
                                    ]
                                ]
                            ]

                        ]
                    ]
                ]
            ];
        } else {

            if ($request->filled('author_id')) {
                $must[] = [
                    'term' => [
                        'author_id' => [
                            'value' => $request->input('author_id')
                        ]
                    ]
                ];
            }

            if ($request->filled('subject')) {
                $must[] = [
                    'match' => [
                        'subject' => $request->input('subject')
                    ]
                ];
            }

            if ($request->filled('text')) {
                $must[] = [
                    'match' => [
                        'text' => $request->input('text')
                    ]
                ];
            }
            $params = [
                'index' => 'tasks-index',
                'body' => [
                    "from" => $offset,
                    "size" => $limit,
                    'query' => [
                        'bool' => [
                            'must' => $must
                        ]
                    ]
                ]
            ];
        }


        $tasks = ClientBuilder::create()->build()->search($params)['hits']['hits'];
        for ($i = 0; $i < count($tasks); $i++) {
            $taskId = $tasks[$i]['_id'];
            $taskBody = $tasks[$i]['_source'];

            $tasks[$i] = [
                "id" => $taskId,
            ];

            $tasks[$i] = array_merge($tasks[$i], $taskBody);
        }
        return ["data" => $tasks,
            "meta" => [
                "offset" => $offset,
                "limit" => $limit
            ]
        ];
    }

    public function create($request)
    {
        $column_names = ["subject", "text", "answer", "author_id"];

        $fieldsToCheck = $request->keys();
        foreach ($fieldsToCheck as $field) {
            if (!in_array($field, $column_names)) {
                throw new Exception("Field '{$field}' does not exist in the database table", 400);
            }
        }

        $missingFields = array_diff($column_names, $request->keys());
        if (count($missingFields) > 0) {
            throw new \Exception("Missing required fields: " . implode(', ', $missingFields), 400);
        }

        $uuid = Uuid::uuid4()->toString();
        $now = Carbon::now(new \DateTimeZone('UTC'));
        $formattedDate = $now->format('Y-m-d\TH:i:s.u\Z');


        $params = [
            'index' => 'tasks-index',
            'id' => $uuid,
            'body' => [
                'subject' => $request["subject"],
                'text' => $request['text'],
                'answer' => $request['answer'],
                'author_id' => $request['author_id'],
                'created_at' => $formattedDate
            ]
        ];

        $response = ClientBuilder::create()->build()->index($params);

        return $this->get($response["_id"]);


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
