<?php

namespace Database\Factories\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'lecturer_id' => Lecturer::factory(),
            'degree' => fake()->randomElement(['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']),
            'field_of_study' => fake()->sentence(3),
            'institution' => fake()->sentence(2),
            'graduation_year' => fake()->numberBetween(1900, date('Y')),
            'thesis_title' => fake()->sentence(5),
        ]; 
   }
}
