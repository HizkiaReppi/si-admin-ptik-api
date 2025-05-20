<?php

namespace App\Interfaces;

interface DashboardRepositoryInterface
{
    public function getTotalSubmissions(): int;
    public function getSubmissionsByStatus(): array;
    public function getSubmissionsByCategory(): array;
    public function getAverageProcessingTime(): float;
    public function getActiveStudentsCount(): int;
    public function getMonthlySubmissionTrends(): array;
}
