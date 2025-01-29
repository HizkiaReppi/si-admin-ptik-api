<?php

namespace Database\Factories\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class ExperienceFactory extends Factory
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
            'position' => fake()->jobTitle(),
            'organization' => fake()->company(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'is_current' => fake()->boolean()
        ]; 
   }
}
