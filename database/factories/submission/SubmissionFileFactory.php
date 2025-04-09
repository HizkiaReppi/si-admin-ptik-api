<?php

namespace Database\Factories\Submission;

use App\Models\Submission\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmissionFile>
 */
class SubmissionFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'submission_id' => Submission::inRandomOrder()->first()->id ?? Submission::factory(),
            'file_path' => 'documents/' . fake()->uuid() . '.pdf',
        ];
    }
}
