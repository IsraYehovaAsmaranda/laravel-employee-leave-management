<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->name(),
            "description" => fake()->text(),
            "due_date" => fake()->dateTimeBetween('now', '+1 year'),
            "criteria" => [
                [
                    "title" => fake()->name(),
                    "description" => fake()->text(),
                    "weight" => fake()->numberBetween(1, 10),
                ]
            ]
        ];
    }
}
