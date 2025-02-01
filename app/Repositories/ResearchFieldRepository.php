<?php

namespace App\Repositories;

use App\Interfaces\ResearchFieldRepositoryInterface;
use App\Models\Lecturers\ResearchField;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchFieldRepository implements ResearchFieldRepositoryInterface
{
    /**
     * Get all lecturers with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $query = ResearchField::query();

        $query->with(['lecturers']);

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];

            $query->where('field_name', 'like', "%{$searchTerm}%");
        }

        return $query->paginate($perPage);
    }


    public function getById(string $id, array $relations = [])
    {
        //
    }

    public function store(array $data)
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
