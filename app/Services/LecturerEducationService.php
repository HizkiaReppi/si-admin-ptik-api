<?php

namespace App\Services;

use App\Repositories\LecturerEducationsRepository;
use Illuminate\Database\Eloquent\Collection;

class LecturerEducationService
{
    private LecturerEducationsRepository $lecturerEducationsRepository;

    public function __construct(LecturerEducationsRepository $lecturerEducationsRepository)
    {
        $this->lecturerEducationsRepository = $lecturerEducationsRepository;
    }

    /**
     * Get data by lecturer id.
     *
     * @param string $lecturerId
     * @return Collection
     */
    public function getByLecturerId(string $lecturerId): Collection
    {
        return $this->lecturerEducationsRepository->getByLecturerId($lecturerId);
    }

    public function update(array $data, string $lecturerId)
    {
        try {
            return $this->lecturerEducationsRepository->update($data, $lecturerId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
