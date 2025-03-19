<?php

namespace App\Repositories\HeadOfDepartment;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\HeadOfDepartmentRepositoryInterface;
use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HeadOfDepartmentRepository implements HeadOfDepartmentRepositoryInterface
{
    public function getAll(): Collection
    {
        return Cache::remember('head_of_departments', 60 * 60 * 24 * 7, function () {
            return HeadOfDepartment::with(['user', 'lecturer'])->get();
        });
    }

    public function getById(string $id): HeadOfDepartment
    {
        return Cache::remember("head_of_department_{$id}", 60 * 60 * 24, function () use ($id) {
            $query = HeadOfDepartment::query();

            $headOfDepartment = $query->find($id);

            if (!$headOfDepartment) {
                throw new ResourceNotFoundException('Head of Department data not found');
            }

            $headOfDepartment->with(['user', 'lecturer']);

            return $headOfDepartment;
        });
    }

    public function store(array $data): HeadOfDepartment
    {
        try {
            return DB::transaction(function () use ($data) {
                $existingHeadOfDepartment = HeadOfDepartment::where('role', $data['role'])->first();

                if ($existingHeadOfDepartment) {
                    $existingHeadOfDepartment->delete();
                    $existingHeadOfDepartment->user->delete();
                }

                $lecturer = Lecturer::with('user')->find($data['lecturer_id']);

                if(!$lecturer) {
                    throw new ResourceNotFoundException('Lecturer data not found');
                }

                $user = User::create([
                    'name' => $lecturer->user->name,
                    'username' => $data['role'] . '_' . $lecturer->user->username,
                    'email' => $data['role'] . '_' . $lecturer->user->email,
                    'password' => bcrypt($data['role'] . '_' . $lecturer->user->username),
                    'role' => 'HoD',
                    'gender' => $lecturer->user->gender,
                    'photo' => $lecturer->user->photo ?? null,
                    'email_verified_at' => now(),
                ]);

                $headOfDepartment = HeadOfDepartment::create([
                    'user_id' => $user->id,
                    'lecturer_id' => $data['lecturer_id'],
                    'role' => $data['role'],
                    'signiture_file' => $data['signiture_file'] ?? null,
                ]);

                return $headOfDepartment;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): HeadOfDepartment
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $headOfDepartment = HeadOfDepartment::find($id);

                if (!$headOfDepartment) {
                    throw new ResourceNotFoundException('Head of Department data not found');
                }

                $lecturer = Lecturer::with('user')->find($data['lecturer_id']);

                if(!$lecturer) {
                    throw new ResourceNotFoundException('Lecturer data not found');
                }

                $user = User::find($headOfDepartment->user_id);

                $user->update([
                    'name' => $lecturer->user->name,
                    'username' => $data['role'] . '_' . $lecturer->user->username,
                    'email' => $data['role'] . '_' . $lecturer->user->email,
                    'password' => bcrypt($data['role'] . '_' . $lecturer->user->username),
                    'role' => 'HoD',

                    'gender' => $lecturer->user->gender,
                    'photo' => $lecturer->user->photo ?? null,
                ]);

                $headOfDepartment->update([
                    'user_id' => $user->id,
                    'lecturer_id' => $data['lecturer_id'],
                    'role' => $data['role'],
                    'signiture_file' => $data['signiture_file'] ?? null,
                ]);

                return $headOfDepartment;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): array
    {
        try {
            return DB::transaction(function () use ($id) {
                $headOfDepartment = HeadOfDepartment::with('user')->find($id);

                if (!$headOfDepartment) {
                    throw new ResourceNotFoundException('Head of Department data not found');
                }

                $signitureFile = $headOfDepartment->signiture_file;

                $headOfDepartment->delete();
                $headOfDepartment->user->delete();

                return [
                    "headOfDepartment" => $headOfDepartment,
                    "signitureFile" => $signitureFile
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
