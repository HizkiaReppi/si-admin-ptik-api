<?php

namespace App\Repositories\Submission;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Category;
use App\Models\Document;
use App\Models\Submission\Submission;
use App\Models\Submission\SubmissionExaminer;
use App\Models\Submission\SubmissionFile;
use App\Models\Submission\SubmissionSupervisor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmissionRepository
{
    public function getAll(string $categorySlug, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "submission_{$categorySlug}_{$perPage}_page_" . request()->get('page', 1) . '_' . md5(json_encode($filters));

        $cacheKeys = Cache::get('submissions_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('submissions_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($categorySlug, $filters, $perPage) {
            $query = Submission::query();

            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });

            $query->with(['student', 'student.user', 'files', 'category']);

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

    public function getById(string $categorySlug, string $id): Submission
    {
        $cacheKey = "submission_{$categorySlug}_{$id}";

        $cacheKeys = Cache::get('submissions_cache_keys', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('submissions_cache_keys', $cacheKeys, 3600);
        }

        return Cache::remember($cacheKey, 3600, function () use ($categorySlug, $id) {
            $query = Submission::query();

            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });

            $query->with(['student', 'student.user', 'student.firstSupervisor', 'student.firstSupervisor.user', 'student.secondSupervisor', 'student.secondSupervisor.user', 'files', 'files.requirement', 'category', 'examiners.examiner', 'examiners.examiner.user', 'supervisors.supervisor', 'supervisors.supervisor.user', 'document']);

            $submission = $query->find($id);

            if (!$submission) {
                throw new ResourceNotFoundException('Submission data not found');
            }

            return $submission;
        });
    }

    public function create(array $data): Submission
    {
        return Submission::create($data);
    }

    /**
     * Update submission status and related fields.
     */
    public function updateStatus(
        Submission $submission,
        string $status,
        ?string $reviewerName = null,
        ?string $reason = null,
        ?string $documentNumber = null,
        ?string $documentDate = null
    ): Submission {
        $submission->update([
            'status' => $status,
            'reviewer_name' => $reviewerName,
            'rejection_reason' => $reason,
        ]);
    
        $document = $submission->document;
    
        if ($document) {
            $document->update([
                'document_date' => $documentDate,
            ]);
        } else {
            $submission->document()->create([
                'document_number' => $documentNumber,
                'document_date' => $documentDate,
            ]);
        }
    
        return $submission->refresh();
    }

    /**
     * Add examiners to a submission.
     */
    public function addExaminers(Submission $submission, array $examinerIds): void
    {
        SubmissionExaminer::where('submission_id', $submission->id)->delete();

        $data = array_map(
            fn($examinerId) => [
                'id' => Str::uuid(),
                'submission_id' => $submission->id,
                'examiner_id' => $examinerId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $examinerIds,
        );

        SubmissionExaminer::insert($data);
    }

    /**
     * Add supervisors to a submission.
     */
    public function addSupervisors(Submission $submission, array $supervisorIds): void
    {
        SubmissionSupervisor::where('submission_id', $submission->id)->delete();

        $data = array_map(
            fn($supervisorId) => [
                'id' => Str::uuid(),
                'submission_id' => $submission->id,
                'supervisor_id' => $supervisorId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $supervisorIds,
        );

        SubmissionSupervisor::insert($data);
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


    public function store(string $categorySlug, string $studentId, array $files): Submission
    {
        return DB::transaction(function () use ($categorySlug, $studentId, $files) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $submission = Submission::create([
                'category_id' => $category->id,
                'student_id' => $studentId,
                'status' => 'submitted',
                'reviewer_name' => null,
                'document_number' => null,
                'document_date' => null,
                'generated_file_path' => null,
                'rejection_reason' => null,
            ]);

            $submissionFiles = array_map(function ($fileData) use ($submission) {
                return [
                    'id' => Str::uuid(),
                    'submission_id' => $submission->id,
                    'requirement_id' => $fileData['requirement_id'],
                    'file_path' => $fileData['file_path'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $files);

            SubmissionFile::insert($submissionFiles);

            return $submission->load(['files.requirement']);
        });
    }

    public function allSubmissionsCount(): int
    {
        return Cache::remember('all_submissions_count', 3600, function () {
            return Submission::count();
        });
    }

    public function getAllByUserId(string $userId, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "submission_{$userId}_{$perPage}_page_" . request()->get('page', 1) . '_' . md5(json_encode($filters));

        $cacheKeys = Cache::get('submissions_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('submissions_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($userId, $filters, $perPage) {
            $query = Submission::query();

            $query->whereHas('student', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });

            $query->with(['student', 'student.user', 'files', 'category']);

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

    public function getAllByStatus(?string $status, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "submission_status_{$status}_{$perPage}_page_" . request()->get('page', 1) . '_' . md5(json_encode($filters));

        $cacheKeys = Cache::get('submissions_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('submissions_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($status, $filters, $perPage) {
            $query = Submission::query();

            $query->with(['student', 'student.user', 'files', 'category']);

            if($status !== null) {
                $query->where('status', $status);
            }

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

            return $query->paginate($perPage);
        });
    }
}
