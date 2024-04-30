<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportedTask>
 */
class ReportedTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => $this->faker->uuid(),
            'subject' => $this->faker->words(1, true),
            'text' =>  $this->faker->text,
            'answer' =>  $this->faker->words(1, true),
            'author_id' => $this->faker->uuid(),
        ];
    }
}
