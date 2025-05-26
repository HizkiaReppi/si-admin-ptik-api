<?php

namespace App\Providers;

use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Category;
use App\Models\Document;
use App\Models\Exam;
use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
use App\Models\Lecturers\ResearchField;
use App\Models\Student;
use App\Models\Submission\Submission;
use App\Models\User;
use App\Observers\AdministratorObserver;
use App\Observers\CategoryObserver;
use App\Observers\DocumentObserver;
use App\Observers\ExamObserver;
use App\Observers\HeadOfDepartmentObserver;
use App\Observers\LecturerObserver;
use App\Observers\ResearchFieldObserver;
use App\Observers\StudentObserver;
use App\Observers\SubmissionObserver;
use App\Repositories\DashboardRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Lecturer::observe(LecturerObserver::class);
        Student::observe(StudentObserver::class);
        ResearchField::observe(ResearchFieldObserver::class);
        HeadOfDepartment::observe(HeadOfDepartmentObserver::class);
        User::observe(AdministratorObserver::class);
        Category::observe(CategoryObserver::class);
        Submission::observe(SubmissionObserver::class);
        Exam::observe(ExamObserver::class);
        Document::observe(DocumentObserver::class);

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
