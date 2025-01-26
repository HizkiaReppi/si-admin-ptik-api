<?php

namespace App\Repositories;

use App\Interfaces\LecturerRepositoryInterface;
use App\Models\Lecturer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LecturerRepository implements LecturerRepositoryInterface
{
    /**
     * Get all lecturers with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $relations = [], array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $query = Lecturer::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        $query->join('users', 'lecturers.user_id', '=', 'users.id')
            ->select([
                'lecturers.id as id',
                'lecturers.nip',
                'lecturers.nidn',
                'lecturers.front_degree',
                'lecturers.back_degree',
                'users.name',
                'users.email'
            ]);

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];

            $query->where(function ($q) use ($searchTerm) {
                $q->where('lecturers.nidn', 'like', "%{$searchTerm}%")
                    ->orWhere('lecturers.nip', 'like', "%{$searchTerm}%")
                    ->orWhere('users.name', 'like', "%{$searchTerm}%");
            });
        }

        if (!empty($filters['sortBy']) && !empty($filters['order'])) {
            $sortBy = $filters['sortBy'];
            $sortOrder = $filters['order'];

            if ($sortBy === 'name') {
                $query->orderBy('users.name', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('users.name', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function getById(string $id, array $relations = []): Lecturer
    {
        return Lecturer::with($relations)->findOrFail($id);
    }

    public function store(array $data): Lecturer
    {
        return Lecturer::create($data);
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
