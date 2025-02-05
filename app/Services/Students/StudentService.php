<?php

namespace App\Services\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\Student;
use App\Repositories\Students\StudentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    private StudentRepository $studentRepository;
    private FormatterHelper $formatterHelper;

    public function __construct(StudentRepository $studentRepository, FormatterHelper $formatterHelper)
    {
        $this->studentRepository = $studentRepository;
        $this->formatterHelper = $formatterHelper;
    }

    /**
     * Get paginated list of students with optional filters and relations.
     */
    public function getAll(array $filters = [], array $relations = [], ?int $perPage = 10): LengthAwarePaginator
    {
        if (!empty($filters['search'])) {
            $filters['search'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower(trim($filters['search'])));
        }

        if (!empty($filters['sortBy']) && !in_array($filters['sortBy'], ['student_name', 'nim'])) {
            $filters['sortBy'] = null;
        }

        if (!empty($filters['order']) && !in_array($filters['order'], ['asc', 'desc'])) {
            $filters['order'] = 'asc';
        }

        return $this->studentRepository->getAll($relations, $filters, $perPage);
    }

    /**
     * Get student by ID with optional relations.
     */
    public function getById(string $id, array $relations = []): Student
    {
        try {
            return $this->studentRepository->getById($id, $relations);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create a new student
     */
    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_student_' . $data['nim'] . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/students', $filePath);
                    $data['photo'] = $filePath;
                }

                $data['lecturer_id_1'] = $data['lecturerId1'];
                $data['lecturer_id_2'] = $data['lecturerId2'];
                unset($data['lecturerId1'], $data['lecturerId2']);

                $result = $this->studentRepository->store($this->formatterHelper->camelToSnake($data));

                if (!$result) {
                    throw new \Exception('Failed to create student');
                }

                return $result;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update a student by ID.
     *
     */
    public function update(array $data, string $id): Student
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $student = $this->studentRepository->getById($id);

                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_student_' . $student->nim . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/students', $filePath);
                    $data['photo'] = $filePath;

                    if ($student->photo !== null) {
                        $oldImagePath = 'public/images/students/' . $student->photo;
                        if (Storage::exists($oldImagePath)) {
                            Storage::delete($oldImagePath);
                        }
                    }
                }

                $data['lecturer_id_1'] = $data['lecturerId1'];
                $data['lecturer_id_2'] = $data['lecturerId2'];
                unset($data['lecturerId1'], $data['lecturerId2']);

                $result = $this->studentRepository->update($this->formatterHelper->camelToSnake($data), $id);

                if (!$result) {
                    throw new \Exception('Failed to update student');
                }

                return $result;
            });
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Delete a student by ID.
     */
    public function delete(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $result = $this->studentRepository->delete($id);

                if (!$result) {
                    throw new \Exception('Failed to delete student');
                }

                if ($result['photo'] !== null) {
                    $oldImagePath = 'public/images/students/' . $result['photo'];
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                return $result;
            });
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
