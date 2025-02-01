<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResearchFieldRepositoryInterface
{
    public function getAll(array $filters, int $perPage): LengthAwarePaginator;
    public function getById(string $id, array $relations);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}
