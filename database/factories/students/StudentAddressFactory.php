<?php

namespace Database\Factories\Students;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Students\StudentAddress>
 */
class StudentAddressFactory extends Factory
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
            'province' => fake()->state(),
            'regency' => fake()->city(),
            'district' => fake()->streetName(),
            'village' => fake()->streetSuffix(),
            'postal_code' => fake()->postcode(),
            'address' => fake()->address(),
            'type' => fake()->randomElement(['domicile', 'origin']),
        ];
   }
}
