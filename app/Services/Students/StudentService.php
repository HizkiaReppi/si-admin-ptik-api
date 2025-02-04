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
        return $this->studentRepository->getAll($relations, $filters, $perPage);
    }

    /**
     * Get lecturer by ID with optional relations.
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
            return $this->studentRepository->store($this->formatterHelper->camelToSnake($data));
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
            return $this->studentRepository->getById($id);               
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
            return $this->studentRepository->delete($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
