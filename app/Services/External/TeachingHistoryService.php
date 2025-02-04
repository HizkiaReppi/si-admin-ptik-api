<?php

namespace App\Services\External;

use App\Repositories\External\TeachingHistoryRepository;

class TeachingHistoryService
{
    protected TeachingHistoryRepository $teachingHistoryRepoitory;

    public function __construct(TeachingHistoryRepository $teachingHistoryRepoitory)
    {
        $this->teachingHistoryRepoitory = $teachingHistoryRepoitory;
    }

    /**
     * Mengambil data teaching history berdasarkan dosenId.
     *
     * @param string $lecturerId
     * @return array
     */
    public function getTeachingHistory(string $lecturerId): array
    {
        return $this->teachingHistoryRepoitory->fetchTeachingHistory($lecturerId);
    }
}
