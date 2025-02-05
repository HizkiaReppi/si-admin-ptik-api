<?php

namespace App\Interfaces;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudentRepositoryInterface
{
    public function getAll(array $relations, array $filters, int $perPage): LengthAwarePaginator;
    public function getById(string $id, array $relations = []): Student;
    public function store(array $data): Student;
    public function update(array $data, string $id);
    public function delete(string $id);
}
