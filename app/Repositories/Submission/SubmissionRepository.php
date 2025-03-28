<?php

namespace App\Repositories\Submission;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Submission\Submission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class SubmissionRepository
{
    public function getAll(string $categorySlug, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "submission_{$categorySlug}_{$perPage}_page_" . request()->get('page', 1) . "_" . md5(json_encode($filters));

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

            $query->with([
                'student', 'student.user', 'student.firstSupervisor', 'student.firstSupervisor.user',
                'student.secondSupervisor', 'student.secondSupervisor.user', 'files', 'category'
            ]);

            $submission = $query->find($id);

            if (!$submission) {
                throw new ResourceNotFoundException("Submission data not found");
            }

            return $submission;
        });
    }

    public function store(string $categorySlug, array $data)
    {
        //
    }

    public function update(array $data, string $id)
    {
        //
    }

    public function delete(string $id)
    {
        //
    }
}
