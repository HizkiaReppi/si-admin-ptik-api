<?php

namespace App\Services\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\Student;
use App\Repositories\Students\StudentInformationRepository;
use Illuminate\Database\Eloquent\Collection;

class StudentInformationService
{
    private StudentInformationRepository $studentInformationRepository;
     private FormatterHelper $formatterHelper;

    public function __construct(StudentInformationRepository $studentInformationRepository, FormatterHelper $formatterHelper)
    {
        $this->studentInformationRepository = $studentInformationRepository;
        $this->formatterHelper = $formatterHelper;
    }

    /**
     * Get data by student id.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getByStudentId(string $studentId): Collection
    {
        return $this->studentInformationRepository->getByStudentId($studentId);
    }

    public function update(array $data, string $studentId): Student
    {
        try {
            return $this->studentInformationRepository->update($this->formatterHelper->camelToSnake($data), $studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $studentId): bool
    {
        try {
            return $this->studentInformationRepository->delete($studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
