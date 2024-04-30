<?php




test(
    'Creation', function () {
        $body = [
        "subject" => "Тестовый предмет",
        "text" => "Тестовый текст",
        "answer" => "Тестовый ответ",
        "author_id" => "cabf0ff5-4e77-34c7-9ab2-64745907cf1a"
        ];
        $response = $this->post('/tasks/api/v1/tasks', $body);
        $response->assertStatus(200)->assertJson(
            [
            "data" => [
                "task_id" => true,
                "subject" => "Тестовый предмет",
                "text" => "Тестовый текст",
                "answer" => "Тестовый ответ",
                "author_id" => "cabf0ff5-4e77-34c7-9ab2-64745907cf1a",
                "created_at" => true,
            ]
            ]
        );
        TaskTest::$task = $response->json("data");
    }
);

test(
    "Search by id", function () {
        $response = $this->get('/tasks/api/v1/tasks/' . TaskTest::$task["task_id"]);

        $response->assertStatus(200)->assertJson(["data" => TaskTest::$task]);
    }
)->depends('Creation');


test(
    "Deletion", function () {
        $response = $this->delete('/tasks/api/v1/tasks/' . TaskTest::$task["task_id"]);

        $response->assertStatus(200)->assertJson(["data" => null]);
    }
)->depends('Creation');




