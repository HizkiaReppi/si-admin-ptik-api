<?php

namespace App\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\ResearchFieldRepositoryInterface;
use App\Models\Lecturers\ResearchField;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

        if (!empty($filters['sortBy']) && !empty($filters['order'])) {
            $sortBy = $filters['sortBy'];
            $sortOrder = $filters['order'];

            if ($sortBy === 'field_name') {
                $query->orderBy('field_name', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        return $query->paginate($perPage);
    }


    public function getById(string $id): ResearchField
    {
        $query = ResearchField::query();

        $query->with(['lecturers']);

        $researchField = $query->find($id);

        if (!$researchField) {
            throw new ResourceNotFoundException("Research Field data not found");
        }

        return $researchField;
    }

    public function store(array $data): ResearchField
    {
        try {
            return DB::transaction(function () use ($data) {
                $researchField = ResearchField::create([
                    'field_name' => $data['field_name'],
                    'description' => $data['description'],
                ]);

                return $researchField;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): ResearchField
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $researchField = ResearchField::find($id);

                if (!$researchField) {
                    throw new ResourceNotFoundException("Research Field data not found");
                }

                if ($data['field_name']) {
                    $researchField->field_name = $data['field_name'];
                }

                $researchField->update([
                    'description' => $data['description'],
                ]);

                return $researchField;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $researchField = ResearchField::find($id);

                if (!$researchField) {
                    throw new ResourceNotFoundException("Research Field data not found");
                }

                $researchField->delete();

                return true;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
