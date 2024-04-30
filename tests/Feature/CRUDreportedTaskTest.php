<?php

class TaskTest
{
    public static $task;
}


test(
    'Creation', function () {
        $body = [
        "subject" => "Тестовый предмет",
        "text" => "Тестовый текст",
        "answer" => "Тестовый ответ",
        "author_id" => "cabf0ff5-4e77-34c7-9ab2-64745907cf1a"
        ];
        $response = $this->post('/tasks/api/v1/reported-tasks', $body);
        $response->assertStatus(201)->assertJson(
            [
            "data" => [
                "task_id" => true,
                "subject" => "Тестовый предмет",
                "text" => "Тестовый текст",
                "answer" => "Тестовый ответ",
                "author_id" => "cabf0ff5-4e77-34c7-9ab2-64745907cf1a",
                "created_at" => true,
                "updated_at" => true,
            ]
            ]
        );
        TaskTest::$task = $response->json("data");
    }
);

test(
    "Search by id", function () {
        $response = $this->get('/tasks/api/v1/reported-tasks/' . TaskTest::$task["task_id"]);

        $response->assertStatus(200)->assertJson(["data" => TaskTest::$task]);
    }
)->depends('Creation');

test(
    "Changing some fields", function () {
        $body = [
        "subject" => "Тестовый предмет после изменения",
        "text" => "Тестовый текст после изменения",
        "answer" => "Тестовый ответ после изменения",
        "author_id" => TaskTest::$task["author_id"]
        ];
        $response = $this->patch('/tasks/api/v1/reported-tasks/' . TaskTest::$task["task_id"], $body);
        $response->assertStatus(200)->assertJson(
            [
            "data" => [
                "task_id" => TaskTest::$task["task_id"],
                "subject" => "Тестовый предмет после изменения",
                "text" => "Тестовый текст после изменения",
                "answer" => "Тестовый ответ после изменения",
                "author_id" => TaskTest::$task["author_id"],
                "created_at" => TaskTest::$task["created_at"],
                "updated_at" => true,
            ]
            ]
        );
    }
)->depends('Creation');

test(
    "Deletion", function () {
        $response = $this->delete('/tasks/api/v1/reported-tasks/' . TaskTest::$task["task_id"]);

        $response->assertStatus(200)->assertJson(["data" => null]);
    }
)->depends('Creation');




