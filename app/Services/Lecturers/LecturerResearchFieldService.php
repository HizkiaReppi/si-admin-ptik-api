<?php

namespace App\Services\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Lecturers\LecturerResearchFieldRepository;
use Illuminate\Database\Eloquent\Collection;

class LecturerResearchFieldService
{
    private LecturerResearchFieldRepository $lecturerResearchFieldRepository;

    public function __construct(LecturerResearchFieldRepository $lecturerResearchFieldRepository)
    {
        $this->lecturerResearchFieldRepository = $lecturerResearchFieldRepository;
    }

    /**
     * Get data by lecturer id.
     *
     * @param string $lecturerId
     * @return Collection
     */
    public function getByLecturerId(string $lecturerId): Collection
    {
        try {
            return $this->lecturerResearchFieldRepository->getByLecturerId($lecturerId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $lecturerId)
    {
        try {
            return $this->lecturerResearchFieldRepository->update($data, $lecturerId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $lecturerId, string $researchFieldId)
    {
        try {
            return $this->lecturerResearchFieldRepository->delete($lecturerId, $researchFieldId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
