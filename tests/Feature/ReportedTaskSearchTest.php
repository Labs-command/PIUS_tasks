<?php

test(
    "Invalid sort_field", function () {
        $body = [
        "search_value" => "123",
        "sort_field" => "test",
        ];
        $response = $this->post('/tasks/api/v1/reported-tasks/search', $body);

        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid sort field"
            ]
        );
    }
);
