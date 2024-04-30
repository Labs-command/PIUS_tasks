<?php


test(
    'Create ReportedTask', function () {
        $response = $this->post('/tasks/api/v1/reported-tasks/search');

        $response->assertStatus(200);
    }
);

