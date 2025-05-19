<?php

namespace App\Repositories\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\LecturerRepositoryInterface;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
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
        $cacheKey = "lecturers_all_{$perPage}_page_" . request()->get('page', 1) . "_" . md5(json_encode($filters));

        $cacheKeys = Cache::get('lecturers_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('lecturers_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($relations, $filters, $perPage) {
            $query = Lecturer::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            $query->join('users', 'lecturers.user_id', '=', 'users.id')->select(['lecturers.id as id', 'lecturers.nip', 'lecturers.nidn', 'lecturers.front_degree', 'lecturers.back_degree', 'users.name', 'users.email', 'users.photo']);

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
        });
    }

    public function getById(string $id, array $relations = []): Lecturer
    {
        $cacheKey = "lecturer_" . $id;

        $cacheKeys = Cache::get('lecturers_cache_keys', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('lecturers_cache_keys', $cacheKeys, 3600);
        }

        return Cache::remember($cacheKey, 3600, function () use ($id, $relations) {
            $query = Lecturer::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            $lecturer = $query->find($id);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            if (in_array('firstSupervisedStudents', $relations) || in_array('secondSupervisedStudents', $relations)) {
                $students = collect();

                if (isset($lecturer->firstSupervisedStudents)) {
                    $students = $students->merge(
                        $lecturer->firstSupervisedStudents->load('user')->map(function ($student) {
                            $student->supervision_status = 'Supervised 1';
                            return $student;
                        })
                    );
                }

                if (isset($lecturer->secondSupervisedStudents)) {
                    $students = $students->merge(
                        $lecturer->secondSupervisedStudents->load('user')->map(function ($student) {
                            $student->supervision_status = 'Supervised 2';
                            return $student;
                        })
                    );
                }

                $lecturer->students = $students->values();
                unset($lecturer->firstSupervisedStudents);
                unset($lecturer->secondSupervisedStudents);
            }

            return $lecturer;
        });
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
                    'gender' => $data['gender'],
                    'photo' => $data['photo'] ?? null,
                    'email_verified_at' => now(),
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

    public function update(array $data, string $id): Lecturer
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $lecturer = Lecturer::find($id);

                if (!$lecturer) {
                    throw new ResourceNotFoundException("Lecturer data not found");
                }

                $lecturer->update([
                    'front_degree' => $data['front_degree'] ?? null,
                    'back_degree' => $data['back_degree'] ?? null,
                    'position' => $data['position'] ?? null,
                    'rank' => $data['rank'] ?? null,
                    'type' => $data['type'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'address' => $data['address'] ?? null,
                ]);

                $lecturer->user->update([
                    'name' => $data['name'],
                    'photo' => $data['photo'] ?? null,
                    'gender' => $data['gender'],
                ]);

                if (isset($data['nidn'])) {
                    $lecturer->update([
                        'nidn' => $data['nidn'],
                    ]);
                    $lecturer->user->update([
                        'username' => $data['nidn'],
                        'password' => bcrypt($data['nidn']),
                    ]);
                }

                if (isset($data['nip'])) {
                    $lecturer->update([
                        'nip' => $data['nip'],
                    ]);
                }

                if (isset($data['email'])) {
                    $lecturer->user->update([
                        'email' => $data['email'],
                    ]);
                }

                return $lecturer;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $lecturer = Lecturer::with('user')->find($id);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $photo = $lecturer->user->photo;

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

    public function countLecturers(): int
    {
        try {
            return Cache::remember('lecturers_count', 3600, function () {
                return Lecturer::count();
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
