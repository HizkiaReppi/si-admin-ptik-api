<?php

namespace Database\Factories\Students;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Students\StudentInformation>
 */
class StudentInformationFactory extends Factory
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
            'national_id_number' => fake()->unique()->numerify('###############'),
            'place_of_birth' => fake()->city(),
            'date_of_birth' => fake()->date(),
            'marital_status' => fake()->randomElement(['Single', 'Married']),
            'religion' => fake()->randomElement(['Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu']),
        ];
   }
}
