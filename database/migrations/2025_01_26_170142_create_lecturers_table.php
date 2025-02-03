<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete(); // Relasi ke users
            $table->string('nip', 18)->unique()->index();
            $table->string('nidn', 10)->unique()->index();
            $table->string('front_degree', 50)->nullable(); // Gelar depan
            $table->string('back_degree', 50)->nullable(); // Gelar belakang
            $table->string('position', 100)->nullable(); // Jabatan akademik
            $table->string('rank', 100)->nullable(); // Pangkat/golongan
            $table->enum('type', ['PNS', 'Honorer', 'Kontrak'])->nullable(); // Status dosen
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable(); // Alamat
            $table->timestamps();
        });

        Schema::create('educations', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->enum('degree', ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'])->index(); // Jenjang pendidikan
            $table->string('field_of_study', 150)->index(); // Bidang studi
            $table->string('institution', 200)->index(); // Institusi pendidikan
            $table->year('graduation_year')->nullable(); // Tahun lulus
            $table->string('thesis_title')->nullable(); // Judul skripsi/tesis/disertasi
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->string('position', 100)->index(); // Posisi/Jabatan
            $table->string('organization')->index(); // Organisasi/Perusahaan
            $table->text('description')->nullable(); // Deskripsi pekerjaan
            $table->date('start_date')->nullable(); // Tanggal mulai
            $table->date('end_date')->nullable(); // Tanggal selesai
            $table->boolean('is_current')->default(false); // Apakah masih aktif
            $table->timestamps();
        });

        Schema::create('research_fields', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('field_name', 200)->unique(); // Nama bidang penelitian
            $table->text('description')->nullable(); // Deskripsi bidang penelitian
            $table->timestamps();
        });

        Schema::create('lecturer_research_fields', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'))->index();
            $table->foreignUuid('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->foreignUuid('research_field_id')->constrained('research_fields')->cascadeOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate associations
            $table->unique(['lecturer_id', 'research_field_id']);
        });

        Schema::create('lecturer_profiles', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->foreignUuid('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->enum('platform', ['pddikti', 'google_scholar', 'sinta', 'scopus', 'researchgate', 'orcid', 'linkedin', 'other']);
            $table->string('profile_url'); // Link ke profil dosen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
        Schema::dropIfExists('educations');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('research_fields');
        Schema::dropIfExists('lecturer_research_fields');
        Schema::dropIfExists('lecturer_profiles');
    }
};
