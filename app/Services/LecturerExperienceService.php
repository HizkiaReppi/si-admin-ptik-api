<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\LecturerExperiencesRepository;
use Illuminate\Database\Eloquent\Collection;

class LecturerExperienceService
{
    private LecturerExperiencesRepository $lecturerExperiencesRepository;

    public function __construct(LecturerExperiencesRepository $lecturerExperiencesRepository)
    {
        $this->lecturerExperiencesRepository = $lecturerExperiencesRepository;
    }

    /**
     * Get data by lecturer id.
     *
     * @param string $lecturerId
     * @return Collection
     */
    public function getByLecturerId(string $lecturerId): Collection
    {
        return $this->lecturerExperiencesRepository->getByLecturerId($lecturerId);
    }

    public function update(array $data, string $lecturerId)
    {
        try {
            return $this->lecturerExperiencesRepository->update($data, $lecturerId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $lecturerId, string $experienceId)
    {
        try {
            return $this->lecturerExperiencesRepository->delete($lecturerId, $experienceId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
