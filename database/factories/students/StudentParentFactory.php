<?php

namespace Database\Factories\Students;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Students\StudentParent>
 */
class StudentParentFactory extends Factory
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
            'student_id' => Student::factory(),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'father_occupation' => fake()->optional()->jobTitle(),
            'mother_occupation' => fake()->optional()->jobTitle(),
            'income' => fake()->optional()->randomElement(['<1M', '1M-3M', '3M-5M', '>5M']),
            'parent_phone_number' => fake()->optional()->phoneNumber(),
        ];
   }
}
