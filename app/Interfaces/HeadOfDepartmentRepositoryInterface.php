<?php

namespace App\Interfaces;

use App\Models\HeadOfDepartment;
use Illuminate\Database\Eloquent\Collection;

interface HeadOfDepartmentRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(string $id): HeadOfDepartment;
    public function store(array $data): HeadOfDepartment;
    public function update(array $data, string $id): HeadOfDepartment;
    public function delete(string $id): array;
}
