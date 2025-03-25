<?php

namespace App\Services\Submission;

use App\Repositories\Submission\SubmissionRepository;
use App\Models\Submission\Submission;
use Illuminate\Pagination\LengthAwarePaginator;

class SubmissionService
{
    public function __construct(protected SubmissionRepository $repository) {}

    public function getAll(string $categorySlug, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAll($categorySlug, $filters, $perPage);
    }

    public function getById(string $categorySlug, string $id): ?Submission
    {
        return $this->repository->getById($categorySlug, $id);
    }
}
