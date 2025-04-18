<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\External\TeachingHistoryController;
use App\Http\Controllers\HeadOfDepartment\HeadOfDepartmentController;
use App\Http\Controllers\Lecturers\LecturerController;
use App\Http\Controllers\Lecturers\LecturerEducationController;
use App\Http\Controllers\Lecturers\LecturerExperienceController;
use App\Http\Controllers\Lecturers\LecturerProfileController;
use App\Http\Controllers\Lecturers\LecturerResearchFieldController;
use App\Http\Controllers\ResearchField\ResearchFieldController;
use App\Http\Controllers\Submission\SubmissionController;
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Students\StudentInformationController;
use App\Http\Controllers\Students\StudentAddressController;
use App\Http\Controllers\Students\StudentParentsController;
use App\Http\Controllers\Submission\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\CacheResponse;

Route::get('/', function () {
    return response()->json([
        'status' => true,
        'code' => 200,
        'message' => 'Hello, Welcome to SI Admin PTIK'
    ], 200);
})->name('api.home');

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum', 'auth'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware(CacheResponse::class)->name('api.user');

        // Lecturers
        Route::apiResource('/lecturers', LecturerController::class)->names('api.lecturers')->except(['index', 'show']);
        Route::get('/lecturers', [LecturerController::class, 'index'])->middleware(CacheResponse::class)->name('api.lecturers.index');
        Route::get('/lecturers/{lecturer}', [LecturerController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.show');

        // Students
        Route::apiResource('/students', StudentController::class)->names('api.students')->except(['index', 'show']);
        Route::get('/students', [StudentController::class, 'index'])->middleware(CacheResponse::class)->name('api.students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->middleware(CacheResponse::class)->name('api.students.show');

        // Head Of Departments
        Route::apiResource('/head-of-departments', HeadOfDepartmentController::class)->names('api.head-of-departments')->except(['index', 'show']);
        Route::get('/head-of-departments', [HeadOfDepartmentController::class, 'index'])->middleware(CacheResponse::class)->name('api.head-of-departments.index');
        Route::get('/head-of-departments/{head_of_department}', [HeadOfDepartmentController::class, 'show'])->middleware(CacheResponse::class)->name('api.head-of-departments.show');

        // Administrators
        Route::apiResource('/administrators', AdminController::class)->names('api.administrators')->except(['index', 'show']);
        Route::get('/administrators', [AdminController::class, 'index'])->middleware(CacheResponse::class)->name('api.administrators.index');
        Route::get('/administrators/{administrator}', [AdminController::class, 'show'])->middleware(CacheResponse::class)->name('api.administrators.show');

        // Categories
        Route::apiResource('/categories', CategoryController::class)->names('api.categories')->except(['index', 'show']);
        Route::get('/categories', [CategoryController::class, 'index'])->middleware(CacheResponse::class)->name('api.categories.index');
        Route::get('/categories/{category}', [CategoryController::class,'show'])->middleware(CacheResponse::class)->name('api.categories.show');

        // Research Fields
        Route::apiResource('/research-fields', ResearchFieldController::class)->names('api.research-fields')->except(['index', 'show']);
        Route::get('/research-fields', [ResearchFieldController::class, 'index'])->middleware(CacheResponse::class)->name('api.research-fields.index');
        Route::get('/research-fields/{research_field}', [ResearchFieldController::class, 'show'])->middleware(CacheResponse::class)->name('api.research-fields.show');

        // Submissions
        Route::apiResource('/submissions/{category}', SubmissionController::class)->names('api.submissions')->except(['index', 'show', 'update', 'destroy']);
        Route::get('/submissions/{category}', [SubmissionController::class, 'index'])->middleware(CacheResponse::class)->name('api.submissions.index');
        Route::get('/submissions/{category}/{submission}', [SubmissionController::class, 'show'])->middleware(CacheResponse::class)->name('api.submissions.show');
        Route::put('/submissions/{category}/{submission}/status', [SubmissionController::class, 'updateStatus'])->name('api.submissions.update-status');
        Route::get('/submissions/{category}/{submission}/generate-document', [SubmissionController::class, 'generateDocument'])->name('api.submissions.generate-document');
        
        // Lecturer Educations
        Route::get('/lecturers/{lecturer_id}/educations/details', [LecturerEducationController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.educations.show');
        Route::put('/lecturers/{lecturer_id}/educations/update', [LecturerEducationController::class, 'update'])->name('api.lecturers.educations.update');
        Route::delete('/lecturers/{lecturer_id}/educations/{education_id}', [LecturerEducationController::class, 'destroy'])->name('api.lecturers.educations.destroy');

        // Lecturer Experiences
        Route::get('/lecturers/{lecturer_id}/experiences/details', [LecturerExperienceController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.experiences.show');
        Route::put('/lecturers/{lecturer_id}/experiences/update', [LecturerExperienceController::class, 'update'])->name('api.lecturers.experiences.update');
        Route::delete('/lecturers/{lecturer_id}/experiences/{experience_id}', [LecturerExperienceController::class, 'destroy'])->name('api.lecturers.experiences.destroy');

        // Lecturer Research Fields
        Route::get('/lecturers/{lecturer_id}/research-fields/details', [LecturerResearchFieldController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.research-fields.show');
        Route::put('/lecturers/{lecturer_id}/research-fields/update', [LecturerResearchFieldController::class, 'update'])->name('api.lecturers.research-fields.update');
        Route::delete('/lecturers/{lecturer_id}/research-fields/{experience_id}', [LecturerResearchFieldController::class, 'destroy'])->name('api.lecturers.research-fields.destroy');

        // Lecturer External Profile
        Route::get('/lecturers/{lecturer_id}/external-profiles/details', [LecturerProfileController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.external-profiles.show');
        Route::put('/lecturers/{lecturer_id}/external-profiles/update', [LecturerProfileController::class, 'update'])->name('api.lecturers.external-profiles.update');
        Route::delete('/lecturers/{lecturer_id}/external-profiles/{profile_id}', [LecturerProfileController::class, 'destroy'])->name('api.lecturers.external-profiles.destroy');

        // Student Information
        Route::get('/students/{student_id}/information/details', [StudentInformationController::class, 'show'])->middleware(CacheResponse::class)->name('api.students.information.show');
        Route::put('/students/{student_id}/information/update', [StudentInformationController::class, 'update'])->middleware(CacheResponse::class)->name('api.students.information.update');
        Route::delete('/students/{student_id}/information/{student_information_id}', [StudentInformationController::class, 'destroy'])->middleware(CacheResponse::class)->name('api.students.information.destroy');

        // Student Address
        Route::get('/students/{student_id}/address/details', [StudentAddressController::class, 'show'])->middleware(CacheResponse::class)->name('api.students.address.show');
        Route::put('/students/{student_id}/address/update', [StudentAddressController::class, 'update'])->middleware(CacheResponse::class)->name('api.students.address.update');
        Route::delete('/students/{student_id}/address/{student_address_id}', [StudentAddressController::class, 'destroy'])->middleware(CacheResponse::class)->name('api.students.address.destroy');

        // Student Parents
        Route::get('/students/{student_id}/parents/details', [StudentParentsController::class, 'show'])->middleware(CacheResponse::class)->name('api.students.parents.show');
        Route::put('/students/{student_id}/parents/update', [StudentParentsController::class, 'update'])->middleware(CacheResponse::class)->name('api.students.parents.update');
        Route::delete('/students/{student_id}/parents/{student_parents_id}', [StudentParentsController::class, 'destroy'])->middleware(CacheResponse::class)->name('api.students.parents.destroy');

        // External API
        Route::get('/external/teaching-history/{lecturerId}', [TeachingHistoryController::class, 'getTeachingHistory'])->middleware(CacheResponse::class)->name('api.external.teaching-history');
    });

    require __DIR__.'/auth.php';
});
