<?php

namespace App\Services;

use App\Repositories\TeachingHistoryRepository;

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
     * @param string $dosenId
     * @return array
     */
    public function getTeachingHistory(string $dosenId): array
    {
        return $this->teachingHistoryRepoitory->fetchTeachingHistory($dosenId);
    }
}
