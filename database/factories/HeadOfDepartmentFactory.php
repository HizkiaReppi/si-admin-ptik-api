<?php

namespace Database\Factories;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeadOfDepartment>
 */
class HeadOfDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lecturer_id' => Lecturer::factory(),
            'role' => fake()->randomElement(['kajur', 'sekjur', 'dekan', 'wakdekan']),
            'signiture_file' => fake()->word(),
        ];
    }
}
