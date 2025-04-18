<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['submitted', 'in_review', 'faculty_review', 'completed', 'rejected'])->default('submitted');
            $table->string('reviewer_name')->nullable();
            $table->string('document_number')->nullable();
            $table->string('document_date')->nullable();
            $table->text('generated_file_path')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('submission_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('submission_examiners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('submission_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('examiner_id')->constrained('lecturers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('submission_supervisors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('submission_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('supervisor_id')->constrained('lecturers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_supervisors');
        Schema::dropIfExists('submission_examiners');
        Schema::dropIfExists('submission_files');
        Schema::dropIfExists('submissions');
    }
};
