<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AdminRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(string $id): User;
    public function store(array $data): User;
    public function update(array $data, string $id): User;
    public function delete(string $id): User;
}
