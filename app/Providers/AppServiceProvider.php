<?php

namespace App\Providers;

use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
use App\Models\Lecturers\ResearchField;
use App\Models\Student;
use App\Models\User;
use App\Observers\AdministratorObserver;
use App\Observers\HeadOfDepartmentObserver;
use App\Observers\LecturerObserver;
use App\Observers\ResearchFieldObserver;
use App\Observers\StudentObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
