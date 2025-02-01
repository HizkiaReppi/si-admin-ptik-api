<?php

namespace Database\Factories\Lecturers;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturers\ResearchField>
 */
class ResearchFieldFactory extends Factory
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
            'field_name' => fake()->word(),
            'description' => fake()->paragraph()
        ];
   }
}
