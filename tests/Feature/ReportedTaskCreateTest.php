<?php

test(
    'Request without body', function () {
        $response = $this->post('/tasks/api/v1/reported-tasks');

        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Missing required fields: subject, text, answer, author_id"
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

test(
    'Request with empty fields', function () {
        $body = [
        "subject" => "",
        "text" => "",
        "answer" => "",
        "author_id" => ""
        ];
        $response = $this->post('/tasks/api/v1/reported-tasks', $body);
        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid author_id uuid"
            ]
        );
    }
);

test(
    'Invalid author_id uuid', function () {
        $body = [
        "subject" => "Тестовый предмет",
        "text" => "Тестовый текст",
        "answer" => "Тестовый ответ",
        "author_id" => "fgfgf"
        ];
        $response = $this->post('/tasks/api/v1/reported-tasks', $body);
        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid author_id uuid"
            ]
        );
    }
);
