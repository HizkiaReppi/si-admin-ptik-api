<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
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
            'user_id' => User::factory(),
            'lecturer_id_1' => Lecturer::factory(),
            'lecturer_id_2' => rand(0, 1) ? Lecturer::factory() : null,
            'nim' => fake()->unique()->numerify('##########'),
            'entry_year' => fake()->year(),
            'class' => fake()->randomElement(['reguler', 'rpl']),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'concentration' => fake()->randomElement(['RPL', 'Multimedia', 'TKJ']),
            'phone_number' => fake()->numerify('08##########'),
        ];
    }
}
