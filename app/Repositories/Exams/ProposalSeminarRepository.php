<?php

namespace App\Repositories\Exams;

use App\Models\Exam;
use App\Models\Submission\Submission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProposalSeminarRepository
{
    public function getAll(array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "proposal_seminar_{$perPage}_page_" . request()->get('page', 1) . '_' . md5(json_encode($filters));

        $cacheKeys = Cache::get('proposal_seminar_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('proposal_seminar_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($filters, $perPage) {
            $query = Exam::query();

            $query->whereHas('submission', function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('slug', 'sk-seminar-proposal');
                });
            });

            $query->with([
                'submission', 
                'submission.category', 
                'submission.student', 
                'submission.student.user', 
                'submission.examiners', 
                'submission.examiners.examiner',
                'submission.examiners.examiner.user'
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

        $lastSubmission = Submission::where('document_number', 'LIKE', "%/{$currentYear}")
            ->orderBy('document_number', 'desc')
            ->first();

        if (!$lastSubmission) {
            return '0001';
        }

        preg_match('/^(\d+)/', $lastSubmission->document_number, $matches);
        $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;

        return str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
