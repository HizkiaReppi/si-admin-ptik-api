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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('lecturer_id_1')->constrained('lecturers')->onDelete('cascade');
            $table->foreignUuid('lecturer_id_2')->nullable()->constrained('lecturers')->onDelete('cascade');
            $table->string('nim', 15)->unique()->index();;
            $table->string('entry_year', 4)->index();
            $table->enum('class', ['reguler', 'rpl'])->default('reguler');
            $table->enum('concentration', ['RPL', 'Multimedia', 'TKJ']);
            $table->string('phone_number', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('student_informations', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->string('national_id_number', 20)->unique();
            $table->string('place_of_birth', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('marital_status', ['Single', 'Married'])->default('Single');
            $table->enum('religion', ['Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'])->nullable();
            $table->timestamps();
        });

        Schema::create('student_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->string('province', 50);
            $table->string('regency', 50);
            $table->string('district', 50);
            $table->string('village', 50);
            $table->string('postal_code', 10)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('student_parents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->string('father_name', 100);
            $table->string('mother_name', 100);
            $table->string('father_occupation', 100)->nullable();
            $table->string('mother_occupation', 100)->nullable();
            $table->string('income', 50)->nullable();
            $table->string('parent_phone_number', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('student_informations');
        Schema::dropIfExists('student_addresses');
        Schema::dropIfExists('student_parents');
    }
};
