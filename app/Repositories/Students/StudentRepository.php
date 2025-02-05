<?php

namespace App\Repositories\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    /**
     * Get all students with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $relations = [], array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "students_all_{$perPage}_page_" . request()->get('page', 1) . "_" . md5(json_encode($filters));

        $cacheKeys = Cache::get('students_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('students_cache_keys', array_unique($cacheKeys), 3600);

        return Cache::remember($cacheKey, 3600, function () use ($relations, $filters, $perPage) {
            $query = Student::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            $query->join('users as student_users', 'students.user_id', '=', 'student_users.id')
                ->leftJoin('lecturers as lecturers_1', 'students.lecturer_id_1', '=', 'lecturers_1.id')
                ->leftJoin('users as lecturer_users', 'lecturers_1.user_id', '=', 'lecturer_users.id')
                ->select([
                    'students.id as id',
                    'students.nim',
                    'students.lecturer_id_1',
                    'student_users.name as student_name',
                    'lecturer_users.name as lecturer_name',
                    'lecturers_1.front_degree as lecturer_front_degree',
                    'lecturers_1.back_degree as lecturer_back_degree'
                ]);

            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('students.nim', 'like', "%{$searchTerm}%")
                        ->orWhere('student_users.name', 'like', "%{$searchTerm}%");
                });
            }

            if (!empty($filters['sortBy']) && !empty($filters['order'])) {
                $sortBy = $filters['sortBy'];
                $sortOrder = $filters['order'];

                if ($sortBy === 'student_name') {
                    $query->orderBy('student_users.name', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            } else {
                $query->orderBy('student_users.name', 'asc');
            }

            return $query->paginate($perPage);
        });
    }

    public function getById(string $id, array $relations = []): Student
    {
        $cacheKey = "student_{$id}";

        $cacheKeys = Cache::get('students_cache_keys', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('students_cache_keys', $cacheKeys, 3600);
        }

        return Cache::remember($cacheKey, 3600, function () use ($id, $relations) {
            $query = Student::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            $student = $query->find($id);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            return $student;
        });
    }

    public function store(array $data): Student
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['nim'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['nim']),
                    'role' => 'student',
                    'gender' => $data['gender'],
                    'photo' => $data['photo'] ?? null,
                ]);

                $studentData = Student::create([
                    'user_id' => $user->id,
                    'lecturer_id_1' => $data['lecturer_id_1'],
                    'lecturer_id_2' => $data['lecturer_id_2'] ?? null,
                    'nim' => $data['nim'],
                    'entry_year' => $data['entry_year'],
                    'class' => $data['class'],
                    'concentration' => $data['concentration'],
                    'phone_number' => $data['phone_number'] ?? null,
                ]);

                return $studentData;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): Student
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $student = Student::find($id);

                if (!$student) {
                    throw new ResourceNotFoundException("Student data not found");
                }

                $student->update([
                    'lecturer_id_1' => $data['lecturer_id_1'],
                    'lecturer_id_2' => $data['lecturer_id_2'] ?? null,
                    'entry_year' => $data['entry_year'],
                    'class' => $data['class'],
                    'concentration' => $data['concentration'],
                    'phone_number' => $data['phone_number'] ?? null,
                ]);

                $student->user->update([
                    'name' => $data['name'],
                    'gender' => $data['gender'],
                ]);

                if (isset($data['nim'])) {
                    $student->update([
                        'nim' => $data['nim'],
                    ]);
                    $student->user->update([
                        'username' => $data['nim'],
                        'password' => bcrypt($data['nim']),
                    ]);
                }

                if (isset($data['email'])) {
                    $student->user->update([
                        'email' => $data['email'],
                    ]);
                }

                if (isset($data['photo'])) {
                    $student->user->update([
                        'photo' => $data['photo'] ?? null,
                    ]);
                }

                return $student;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): array
    {
        DB::beginTransaction();
        try {
            $student = Student::find($id);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            $photo = $student->photo;

            $student->user->delete();
            $student->delete();

            DB::commit();

            return [
                "student" => $student,
                "photo" => $photo
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
