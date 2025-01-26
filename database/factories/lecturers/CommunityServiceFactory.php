<?php

namespace Database\Factories\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class CommunityServiceFactory extends Factory
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
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'year' => fake()->year(),
            'funding_source' => fake()->sentence(2),
            'budget' => fake()->randomFloat(2, 1000, 1000000)
        ]; 
   }
}
