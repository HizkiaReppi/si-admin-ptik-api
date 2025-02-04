<?php

namespace App\Repositories\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    /**
     * Get all students with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $relations = [], array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        //
    }

    public function getById(string $id, array $relations = []): Student
    {
        //
    }

    public function store(array $data): Student
    {
        //
    }

    public function update(array $data, string $id): Student
    {
        //
    }

    public function delete(string $id)
    {
        //
    }
}
