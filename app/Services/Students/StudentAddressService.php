<?php

namespace App\Services\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\Student;
use App\Repositories\Students\StudentAddressRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class StudentAddressService
{
    public function __construct(
        protected StudentAddressRepository $studentAddressRepository, 
        protected FormatterHelper $formatterHelper
    ) {}

    /**
     * Get data by student id.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getByStudentId(string $studentId): Collection
    {
        return $this->studentAddressRepository->getByStudentId($studentId);
    }

    public function update(array $data, string $studentId)
    {
        try {
            $formattedData = array($this->formatterHelper->camelToSnake($data));

            return $this->studentAddressRepository->update($formattedData, $studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $studentId): bool
    {
        try {
            return $this->studentAddressRepository->delete($studentId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
