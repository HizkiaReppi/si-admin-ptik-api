<?php

namespace App\Repositories;

use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Student;
use App\Models\Submission\Submission;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalSubmissions(): int
    {
        return Submission::count();
    }

    public function getSubmissionsByStatus(): array
    {
        return Submission::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function getSubmissionsByCategory(): array
    {
        return Submission::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category:id,name')
            ->get()
            ->mapWithKeys(fn($item) => [$item->category->name => $item->total])
            ->toArray();
    }

    public function getAverageProcessingTime(): float
    {
        return (float) Submission::whereNotNull('updated_at')
            ->whereNotNull('created_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_seconds'))
            ->value('avg_seconds') / 3600; // dalam jam
    }

    public function getActiveStudentsCount(): int
    {
        return Student::has('submissions')->count();
    }

    public function getMonthlySubmissionTrends(): array
    {
        return Submission::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }
}
