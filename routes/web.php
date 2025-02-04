<?php

use App\Http\Controllers\External\TeachingHistoryController;
use App\Http\Controllers\Lecturers\LecturerController;
use App\Http\Controllers\Lecturers\LecturerEducationController;
use App\Http\Controllers\Lecturers\LecturerExperienceController;
use App\Http\Controllers\Lecturers\LecturerProfileController;
use App\Http\Controllers\Lecturers\LecturerResearchFieldController;
use App\Http\Controllers\ResearchField\ResearchFieldController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\CacheResponse;

Route::get('/', function () {
    return response()->json([
        'status' => true,
        'code' => 200,
        'message' => 'Hello, Welcome to SI Admin PTIK'
    ], 200);
});

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum', 'auth'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware(CacheResponse::class);

        // Lecturers
        Route::apiResource('/lecturers', LecturerController::class)->names('api.lecturers')->except(['index', 'show']);
        Route::get('/lecturers', [LecturerController::class, 'index'])->middleware(CacheResponse::class)->name('api.lecturers.index');
        Route::get('/lecturers/{lecturer}', [LecturerController::class, 'show'])->middleware(CacheResponse::class)->name('api.lecturers.show');

        // Research Fields
        Route::apiResource('/research-fields', ResearchFieldController::class)->names('api.research-fields')->except(['index', 'show']);
        Route::get('/research-fields', [ResearchFieldController::class, 'index'])->middleware(CacheResponse::class)->name('api.research-fields.index');
        Route::get('/research-fields/{research_field}', [ResearchFieldController::class, 'show'])->middleware(CacheResponse::class)->name('api.research-fields.show');

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

        // External API
        Route::get('/external/teaching-history/{lecturerId}', [TeachingHistoryController::class, 'getTeachingHistory'])->middleware(CacheResponse::class)->name('api.external.teaching-history');
    });

    require __DIR__.'/auth.php';
});
