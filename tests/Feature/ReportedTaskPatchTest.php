<?php

test(
    "Non-exsistence id in url ", function () {
        $response = $this->patch('/tasks/api/v1/reported-tasks/4a34f60f-499f-4b1b-bd5a-77cc86beb5db');

        $response->assertStatus(404)->assertJson(
            [
            "errors" => "Task not found"
            ]
        );
    }
);

test(
    "Invalid id in url", function () {
        $response = $this->patch('/tasks/api/v1/reported-tasks/hg');

        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid uuid"
            ]
        );
    }
);

test(
    'Request with invalid field_name', function () {
        $body = [
        "subject" => "Тестовый предмет",
        "text123" => "Тестовый текст",
        "answer" => "Тестовый ответ",
        "author_id" => "cabf0ff5-4e77-34c7-9ab2-64745907cf1a"
        ];
        $response = $this->post('/tasks/api/v1/reported-tasks', $body);
        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Field 'text123' does not exist in the database table"
            ]
        );
    }
);
