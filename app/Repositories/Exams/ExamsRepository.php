<?php

namespace App\Repositories\Exams;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Document;
use App\Models\Exam;
use App\Models\Submission\Submission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExamsRepository
{
    public function getAll(string $slug, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "{$slug}_{$perPage}_page_" . request()->get('page', 1) . '_' . md5(json_encode($filters));

        $cacheKeys = Cache::get('exams_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('exams_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($slug, $filters, $perPage) {
            $query = Exam::query();

            $query->whereHas('submission', function ($query) use ($slug) {
                $query->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                });
            });

            $query->with([
                'submission', 
                'submission.category', 
                'submission.student', 
                'submission.student.firstSupervisor', 
                'submission.student.firstSupervisor.user', 
                'submission.student.secondSupervisor', 
                'submission.student.secondSupervisor.user', 
                'submission.student.user', 'submission.examiners', 
                'submission.examiners.examiner', 
                'submission.examiners.examiner.user',
                'document'
            ]);

            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];

                $query->whereHas('student', function ($query) use ($searchTerm) {
                    $query->whereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', "%{$searchTerm}%");
                    });
                });
            }

            if (!empty($filters['sortBy']) && !empty($filters['order'])) {
                $sortBy = $filters['sortBy'];
                $sortOrder = $filters['order'];

                if ($sortBy === 'status') {
                    $query->orderBy('status', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            }

            $query->orderBy('created_at', 'desc');

            return $query->paginate($perPage);
        });
    }

    public function getById(string $id)
    {
        $exam = Exam::with([
                'submission', 
                'submission.category', 
                'submission.student', 
                'submission.student.firstSupervisor', 
                'submission.student.firstSupervisor.user', 
                'submission.student.secondSupervisor', 
                'submission.student.secondSupervisor.user', 
                'submission.student.user', 
                'submission.examiners', 
                'submission.examiners.examiner', 
                'submission.examiners.examiner.user',
                'document'
            ])
            ->where('submission_id', $id)
            ->first();

        if (!$exam) {
            throw new ResourceNotFoundException('Exam data not found');
        }

        return $exam;
    }

    public function create(string $submissionId): Exam
    {
        try {
            return Exam::create([
                'submission_id' => $submissionId,
                'exam_date' => null,
                'exam_time' => null,
                'exam_place' => null,
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $submissionId, array $data, string $documentNumber, string $documentDate): ?Exam
    {
        DB::beginTransaction();
        try {
            $exam = Exam::where('submission_id', $submissionId)->first();

            if (!$exam) {
                throw new ResourceNotFoundException('Exam data not found');
            }

            $exam->update([
                'exam_date' => $data['exam_date'] ?? $exam->exam_date,
                'exam_time' => $data['exam_time'] ?? $exam->exam_time,
                'exam_place' => $data['exam_place'] ?? $exam->exam_place,
            ]);

            $document = $exam->document;

            if ($document) {
                $document->update([
                    'document_number' => $documentNumber,
                    'document_date' => $documentDate,
                ]);
            } else {
                $exam->document()->create([
                    'document_number' => $documentNumber,
                    'document_date' => $documentDate,
                ]);
            }

            DB::commit();

            return $exam;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Generate document number based on category and year.
     */
    public function generateDocumentNumber(string $categoryCode, int $year): string
    {
        $count = Submission::whereYear('created_at', $year)->whereHas('category', fn($query) => $query->where('code', $categoryCode))->whereNotNull('document_number')->count() + 1;

        return sprintf('%s/%03d/%d', $categoryCode, $count, $year);
    }

    public function getLastDocumentNumber(): string
    {
        $currentYear = date('Y');

        $lastDocument = Document::where('document_number', 'LIKE', "%/{$currentYear}")
            ->orderBy('document_number', 'desc')
            ->first();

        if (!$lastDocument) {
            return '0001';
        }

        preg_match('/^(\d+)/', $lastDocument->document_number, $matches);
        $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;

        return str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getBySubmissionId(string $submissionId): ?Exam
    {
        return Exam::where('submission_id', $submissionId)->first();
    }

    public function delete(string $submissionId): ?Exam
    {
        DB::beginTransaction();
        try {
            $exam = Exam::where('submission_id', $submissionId)->first();

            if ($exam) {
                $exam->delete();

                DB::commit();

                return $exam;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
