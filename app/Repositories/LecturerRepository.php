<?php

namespace App\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\LecturerRepositoryInterface;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LecturerRepository implements LecturerRepositoryInterface
{
    /**
     * Get all lecturers with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $relations = [], array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $query = Lecturer::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        $query->join('users', 'lecturers.user_id', '=', 'users.id')->select(['lecturers.id as id', 'lecturers.nip', 'lecturers.nidn', 'lecturers.front_degree', 'lecturers.back_degree', 'users.name', 'users.email']);

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];

            $query->where(function ($q) use ($searchTerm) {
                $q->where('lecturers.nidn', 'like', "%{$searchTerm}%")
                    ->orWhere('lecturers.nip', 'like', "%{$searchTerm}%")
                    ->orWhere('users.name', 'like', "%{$searchTerm}%");
            });
        }

        if (!empty($filters['sortBy']) && !empty($filters['order'])) {
            $sortBy = $filters['sortBy'];
            $sortOrder = $filters['order'];

            if ($sortBy === 'name') {
                $query->orderBy('users.name', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('users.name', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function getById(string $id, array $relations = []): Lecturer
    {
        return Lecturer::with($relations)->findOrFail($id);
    }

    public function store(array $data): Lecturer
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['nidn'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['nidn']),
                    'role' => 'lecturer',
                ]);

                $lecturerData = Lecturer::create([
                    'nip' => $data['nip'],
                    'nidn' => $data['nidn'],
                    'front_degree' => $data['front_degree'] ?? null,
                    'back_degree' => $data['back_degree'] ?? null,
                    'position' => $data['position'] ?? null,
                    'rank' => $data['rank'] ?? null,
                    'type' => $data['type'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'address' => $data['address'] ?? null,
                    'user_id' => $user->id,
                ]);

                return $lecturerData;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id)
    {
        //
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $lecturer = Lecturer::find($id);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $photo = $lecturer->photo;

            $lecturer->user->delete();
            $lecturer->delete();

            DB::commit();

            return [
                "lecturer" => $lecturer,
                "photo" => $photo
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
