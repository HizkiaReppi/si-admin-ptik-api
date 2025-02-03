<?php

namespace Database\Factories\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturers\LecturerProfile>
 */
class LecturerProfileFactory extends Factory
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
            'platform' => fake()->randomElement(['pddikti', 'google_scholar', 'sinta', 'scopus', 'researchgate', 'orcid', 'linkedin', 'other']),
            'profile_url' => fake()->url(),
        ];
   }
}
