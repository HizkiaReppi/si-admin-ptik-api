<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAll(array $filters, int $perPage): LengthAwarePaginator;
    public function getById(string $id): Category;
    public function store(array $data): Category;
    public function update(array $data, string $id): Category;
    public function delete(string $id): bool;
}
