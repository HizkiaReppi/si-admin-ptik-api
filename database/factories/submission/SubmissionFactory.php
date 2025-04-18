<?php

namespace Database\Factories\Submission;

use App\Models\Student;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['submitted', 'in_review', 'faculty_review', 'completed', 'rejected']);
        $created_at = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'id' => Str::uuid(),
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'status' => $status,
            'reviewer_name' => $status !== 'in_review' && $status !== 'submitted' ? fake()->name() : null,
            'document_number' => $status !== 'in_review' && $status !== 'submitted' ?  fake()->regexify('SK/\d{8}/[a-zA-Z0-9]{5}') : null,
            'document_date' => $status !== 'in_review' && $status !== 'submitted' ? fake()->dateTimeBetween($created_at, 'now') : null,
            'generated_file_path' => $status === 'completed' ? fake()->filePath() : null,
            'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
            'reviewer_name' => User::where('role', 'admin')->inRandomOrder()->first()->name ?? User::factory(),
            'created_at' => $created_at,
            'updated_at' => fake()->dateTimeBetween($created_at, 'now'),
        ];
    }
}
