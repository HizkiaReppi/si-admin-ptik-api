<?php

namespace Database\Factories\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class PublicationFactory extends Factory
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
            'publication_type' => fake()->randomElement(['article', 'book', 'proceeding', 'other']),
            'publisher' => fake()->company(),
            'publication_date' => fake()->date(),
            'doi' => null,
            'issn' => null,
            'isbn' => null,
            'author' => null,
        ]; 
   }
}
