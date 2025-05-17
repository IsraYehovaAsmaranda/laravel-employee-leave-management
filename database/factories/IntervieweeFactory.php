<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interviewee>
 */
class IntervieweeFactory extends Factory
{
    public function definition(): array
    {
        // Simpan file palsu dari folder faker ke interviewees
        $newFile = fake()->file(
            Storage::disk('public')->path('faker'),
            Storage::disk('public')->path('interviewees')
        );

        // Ambil path relatif setelah 'storage/app/public/'
        $relativePath = Str::after($newFile, Storage::disk('public')->path(''));

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'phone' => fake()->numerify('08##########'), // format 12 digit seperti 081234567890
            'cv' => str_replace('\\', '/', $relativePath), // untuk cross-platform (Windows/Linux)
            'metadata' => [
                'experience' => [
                    $this->generateExperience(),
                    $this->generateExperience(),
                ],
                'skill' => [
                    $this->generateSkill(),
                    $this->generateSkill(),
                ]
            ],
        ];
    }

    private function generateExperience(): array
    {
        $start = fake()->dateTimeBetween('-2 years', '-1 year');
        $end = fake()->dateTimeBetween($start, 'now');

        return [
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ];
    }

    private function generateSkill(): array
    {
        return [
            'name' => fake()->word(),
            'level' => fake()->numberBetween(1, 10),
        ];
    }
}
