<?php

namespace Database\Factories\Submission;

use App\Models\Student;
use App\Models\Category;
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
        
        return [
            'id' => Str::uuid(),
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'status' => $status,
            'reviewer_name' => $status !== 'in_review' && $status !== 'submitted' ? fake()->name() : null,
            'document_number' => $status !== 'in_review' && $status !== 'submitted' ?  fake()->optional()->regexify('SK/\d{8}/[a-zA-Z0-9]{5}') : null,
            'generated_file_path' => $status === 'completed' ? fake()->optional()->filePath() : null,
            'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
        ];
    }
}
