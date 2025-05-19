<?php

namespace App\Services;

use App\Interfaces\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        protected DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function getDashboardMetrics(): array
    {
        return [
            'total_submissions' => $this->dashboardRepository->getTotalSubmissions(),
            'submissions_by_status' => $this->dashboardRepository->getSubmissionsByStatus(),
            'submissions_by_category' => $this->dashboardRepository->getSubmissionsByCategory(),
            'average_processing_time_hours' => round($this->dashboardRepository->getAverageProcessingTime(), 2),
            'active_students' => $this->dashboardRepository->getActiveStudentsCount(),
            'monthly_trends' => $this->dashboardRepository->getMonthlySubmissionTrends(),
        ];
    }
}
