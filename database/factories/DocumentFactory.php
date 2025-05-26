<?php

namespace Database\Factories\Submission;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models>
 */
class SubmissDocumentFactoryionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $created_at = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'document_number' => fake()->regexify('SK/\d{8}/[a-zA-Z0-9]{5}'),
            'document_date' => fake()->dateTimeBetween($created_at, 'now')
        ];
    }
}
