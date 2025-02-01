<?php

namespace App\Services;

use App\Repositories\ResearchFieldRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchFieldService
{
    private ResearchFieldRepository $researchFieldRepository;

    public function __construct(ResearchFieldRepository $researchFieldRepository)
    {
        $this->researchFieldRepository = $researchFieldRepository;
    }

    /**
     * Get paginated list of research field with optional filters.
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

        return $this->researchFieldRepository->getAll($filters, $perPage);
    }
}
