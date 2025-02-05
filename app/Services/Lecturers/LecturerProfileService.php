<?php

namespace App\Services\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Repositories\Lecturers\LecturerProfileRepository;
use Illuminate\Database\Eloquent\Collection;

class LecturerProfileService
{
    private LecturerProfileRepository $lecturerProfileRepository;
    private FormatterHelper $formatterHelper;

    public function __construct(LecturerProfileRepository $lecturerProfileRepository, FormatterHelper $formatterHelper)
    {
        $this->lecturerProfileRepository = $lecturerProfileRepository;
        $this->formatterHelper = $formatterHelper;
    }

    /**
     * Get data by lecturer id.
     *
     * @param string $lecturerId
     * @return Collection
     */
    public function getByLecturerId(string $lecturerId): Collection
    {
        return $this->lecturerProfileRepository->getByLecturerId($lecturerId);
    }

    public function update(array $data, string $lecturerId)
    {
        try {
            return $this->lecturerProfileRepository->update($this->formatterHelper->camelToSnake($data), $lecturerId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $lecturerId, string $educationId)
    {
        try {
            return $this->lecturerProfileRepository->delete($lecturerId, $educationId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
