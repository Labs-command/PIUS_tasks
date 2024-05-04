<?php

test(
    "Invalid id in url", function () {
        $response = $this->get('/tasks/api/v1/tasks/hg');

        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid uuid"
            ]
        );
    }
);

test(
    "Non-exsistence id in url ", function () {
        $response = $this->get('/tasks/api/v1/tasks/4a34f60f-499f-4b1b-bd5a-77cc86beb5db');

        $response->assertStatus(404)->assertJson(
            [
            "errors" => "Task not found"
            ]
        );
    }
);


