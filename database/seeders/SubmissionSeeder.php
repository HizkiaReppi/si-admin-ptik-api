<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Lecturer;
use App\Models\Submission\Submission;
use App\Models\Submission\SubmissionFile;
use App\Models\Submission\SubmissionExaminer;
use App\Models\Submission\SubmissionSupervisor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Submission::factory(50)->create()->each(function ($submission) {
            foreach ($submission->category->requirements as $requirement) {
                if($requirement->type === 'text') {
                    SubmissionFile::factory()->create([
                        'submission_id' => $submission->id,
                        'file_path' => 'Judul Skripsi Mahasiswa',
                        'requirement_id' => $requirement->id,
                    ]);
                } else if ($requirement->type === 'document') {
                    SubmissionFile::factory()->create([
                        'submission_id' => $submission->id,
                        'file_path' => 'public/file/submissions/' . time() . '_' . $submission->category->slug . '_' . $requirement->name . '.pdf',
                        'requirement_id' => $requirement->id,
                    ]);
                } else if ($requirement->type === 'photo') {
                    SubmissionFile::factory()->create([
                        'submission_id' => $submission->id,
                        'file_path' => 'public/photo/submissions/' . time() . '_' . $submission->category->slug . '_' . $requirement->name . '.png',
                        'requirement_id' => $requirement->id,
                    ]);
                }
            }

            $firstSupervisorId = $submission->student->firstSupervisor->id ?? null;

            if ($submission->category->slug === 'sk-seminar-proposal' && $firstSupervisorId) {
                SubmissionExaminer::factory()->create([
                    'submission_id' => $submission->id,
                    'examiner_id' => $firstSupervisorId,
                ]);

                if (!in_array($submission, ['faculty_review', 'completed'])) {
                    $existingExaminers = [$firstSupervisorId];

                    SubmissionExaminer::factory(3)->create([
                        'submission_id' => $submission->id,
                    ])->each(function ($examiner) use (&$existingExaminers) {
                        while (in_array($examiner->examiner_id, $existingExaminers)) {
                            $examiner->examiner_id = Lecturer::inRandomOrder()->whereNotIn('id', $existingExaminers)->first()->id;
                            $examiner->save();
                        }
                        $existingExaminers[] = $examiner->examiner_id;
                    });
                }
            }

            if ($submission->category->slug === 'sk-ujian-hasil-penelitian' && $firstSupervisorId) {
                SubmissionExaminer::factory()->create([
                    'submission_id' => $submission->id,
                    'examiner_id' => $firstSupervisorId,
                ]);

                if (!in_array($submission, ['faculty_review', 'completed'])) {
                    $existingExaminers = [$firstSupervisorId];

                    SubmissionExaminer::factory(4)->create([
                        'submission_id' => $submission->id,
                    ])->each(function ($examiner) use (&$existingExaminers) {
                        while (in_array($examiner->examiner_id, $existingExaminers)) {
                            $examiner->examiner_id = Lecturer::inRandomOrder()->whereNotIn('id', $existingExaminers)->first()->id;
                            $examiner->save();
                        }
                        $existingExaminers[] = $examiner->examiner_id;
                    });
                }
            }

            // Cek kategori SK Pembimbing Skripsi
            if ($submission->category->slug === 'sk-pembimbing-skripsi' && $firstSupervisorId) {
                SubmissionSupervisor::factory()->create([
                    'submission_id' => $submission->id,
                    'supervisor_id' => $firstSupervisorId,
                ]);

                if (!in_array($submission, ['faculty_review', 'completed'])) {
                    $existingSupervisors = [$firstSupervisorId];

                    SubmissionSupervisor::factory()->create([
                        'submission_id' => $submission->id,
                    ])->each(function ($supervisor) use (&$existingSupervisors) {
                        while (in_array($supervisor->supervisor_id, $existingSupervisors)) {
                            $supervisor->supervisor_id = Lecturer::inRandomOrder()->whereNotIn('id', $existingSupervisors)->first()->id;
                            $supervisor->save();
                        }
                        $existingSupervisors[] = $supervisor->supervisor_id;
                    });
                }
            }

            if ($submission->status === 'completed') {
                Exam::insert([
                    'id' => Str::uuid(),
                    'submission_id' => $submission->id,
                    'exam_date' => now()->addDays(7),
                    'exam_time' => now()->addHours(2),
                    'exam_place' => 'Ruang Ujian',
                ]);
            }
        });
    }
}
