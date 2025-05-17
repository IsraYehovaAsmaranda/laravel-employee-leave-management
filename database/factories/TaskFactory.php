<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $newFile = fake()->file(Storage::disk("public")->path("faker"), Storage::disk("public")->path("tasks"));
        return [
            "title" => fake()->name(),
            "description" => fake()->text(),
            "due_date" => fake()->dateTimeBetween('now', '+1 year'),
            "attachment" => Str::after($newFile, Storage::disk('public')->path('')),
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
