<?php

test(
    "Invalid id in url", function () {
        $response = $this->delete('/tasks/api/v1/reported-tasks/hg');

        $response->assertStatus(400)->assertJson(
            [
            "errors" => "Invalid uuid"
            ]
        );
    }
);

test(
    "Removing a non-existent uuid", function () {
        $response = $this->delete('/tasks/api/v1/reported-tasks/cabf0ff5-4e77-34c7-9ab2-64745907cf1a');

        $response->assertStatus(200)->assertJson(
            [
            "data" => null,
            ]
        );
    }
)->repeat(2);
