<?php

namespace Database\Factories;

use App\Helpers\LecturerHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
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
            'nip' => fake()->unique()->numerify('##################'),
            'nidn' => fake()->unique()->numerify('##########'),
            'front_degree' => null,
            'back_degree' => null,
            'position' => null,
            'rank' => null,
            'type' => 'PNS',
            'phone_number' => fake()->numerify('08##########'),
            'address' => fake()->address(),
        ];
    }
}
