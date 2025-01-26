<?php

namespace App\Interfaces;

use App\Models\Lecturer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LecturerRepositoryInterface
{
    public function getAll(array $relations, array $filters, int $perPage): LengthAwarePaginator;
    public function getById(string $id, array $relations = []): Lecturer;
    public function store(array $data): Lecturer;
    public function update(array $data, string $id);
    public function delete(string $id);
}
