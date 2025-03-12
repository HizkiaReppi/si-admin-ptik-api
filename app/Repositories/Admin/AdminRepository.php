<?php

namespace App\Repositories\Admin;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\AdminRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminRepository implements AdminRepositoryInterface
{
    public function getAll(): Collection
    {
        return Cache::remember('administrators', 60 * 60 * 24 * 7, function () {
            return User::where('role', 'admin')->get();
        });
    }

    public function getById(string $id): User
    {
        return Cache::remember("administrator_{$id}", 60 * 60 * 24 * 7, function () use ($id) {
            $query = User::query();

            $admin = $query->where('role', 'admin')->find($id);

            if (!$admin) {
                throw new ResourceNotFoundException('Admin data not found');
            }

            return $admin;
        });
    }

    public function store(array $data): User
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'role' => 'admin',
                    'gender' => $data['gender'],
                    'photo' => $data['photo'] ?? null,
                    'email_verified_at' => now(),
                ]);

                return $user;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): User
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $user = User::find($id);

                if (!$user) {
                    throw new ResourceNotFoundException('Admin data not found');
                }

                $user->update([
                    'name' => $data['name'],
                    'gender' => $data['gender'],
                    'photo' => $data['photo'] ?? null,
                ]);
                
                if (isset($data['username'])) {
                    $user->update([
                        'username' => $data['username']
                    ]);
                }
                
                if (isset($data['email'])) {
                    $user->update([
                        'email' => $data['email']
                    ]);
                }
                
                if (isset($data['password'])) {
                    $user->update([
                        'password' => bcrypt($data['password'])
                    ]);
                }

                return $user;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): User
    {
        try {
            return DB::transaction(function () use ($id) {
                $user = User::find($id);

                if (!$user) {
                    throw new ResourceNotFoundException('Admin data not found');
                }

                $user->delete();

                return $user;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
