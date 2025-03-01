<?php

namespace App\Services\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\Student;
use App\Repositories\Students\StudentParentsRepository;
use Illuminate\Database\Eloquent\Collection;

class StudentParentsService
{
    public function __construct(
        protected StudentParentsRepository $studentParentsRepository, 
        protected FormatterHelper $formatterHelper,
    ) {}

    /**
     * Get data by student id.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getByStudentId(string $studentId): Collection
    {
        return $this->studentParentsRepository->getByStudentId($studentId);
    }

    public function update(array $data, string $studentId): Student
    {
        try {
            return $this->studentParentsRepository->update($this->formatterHelper->camelToSnake($data), $studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $studentId): bool
    {
        try {
            return $this->studentParentsRepository->delete($studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
