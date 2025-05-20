<?php

namespace App\Services\Exams;

use App\Repositories\Exams\ProposalSeminarRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProposalSeminarService
{
    public function __construct(
        protected ProposalSeminarRepository $repository
    ) { }

    /**
     * Get paginated list of lecturers with optional filters and relations.
     *
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], ?int $perPage = 10): LengthAwarePaginator
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

        return $this->repository->getAll($filters, $perPage);
    }
}
