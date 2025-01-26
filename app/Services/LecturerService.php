<?php

namespace App\Services;

use App\Repositories\LecturerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LecturerService
{
    private LecturerRepository $lecturerRepository;

    public function __construct(LecturerRepository $lecturerRepository)
    {
        $this->lecturerRepository = $lecturerRepository;
    }

    /**
     * Get paginated list of lecturers with optional filters and relations.
     *
     * @param array $filters
     * @param array $relations
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getLecturers(array $filters = [], array $relations = [], ?int $perPage = 10): LengthAwarePaginator
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

        return $this->lecturerRepository->getAll($relations, $filters, $perPage);
    }
}