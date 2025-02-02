<?php

namespace App\Interfaces;

use App\Models\Lecturers\ResearchField;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResearchFieldRepositoryInterface
{
    public function getAll(array $filters, int $perPage): LengthAwarePaginator;
    public function getById(string $id): ResearchField;
    public function store(array $data): ResearchField;
    public function update(array $data, string $id): ResearchField;
    public function delete(string $id): bool;
}
