<?php

namespace App\Services\Exams;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Exam;
use App\Repositories\Exams\ExamsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExamsService
{
    public function __construct(protected ExamsRepository $repository) {}

    /**
     * Get paginated list of lecturers with optional filters and relations.
     *
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(string $slug, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        if (!empty($filters['search'])) {
            $filters['search'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower(trim($filters['search'])));
        }

        if (!empty($filters['sortBy']) && !in_array($filters['sortBy'], ['name', 'nip', 'nidn'])) {
            $filters['sortBy'] = null;
        }

        if (!empty($filters['order']) && !in_array($filters['order'], ['asc', 'desc'])) {
            $filters['order'] = 'asc';
        }

        return $this->repository->getAll($slug, $filters, $perPage);
    }

    public function create(string $submissionId): Exam
    {
        try {
            return $this->repository->create($submissionId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $submissionId, array $data): ?Exam
    {
        try {
            return $this->repository->update($submissionId, $data);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
