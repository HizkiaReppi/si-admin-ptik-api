<?php

use App\Http\Controllers\LecturerController;
use App\Http\Controllers\LecturerEducationController;
use App\Http\Controllers\LecturerExperienceController;
use App\Http\Controllers\LecturerResearchFieldController;
use App\Http\Controllers\ResearchFieldController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        });

        Route::apiResource('/lecturers', LecturerController::class)->names('api.lecturers');
        Route::apiResource('/research-fields', ResearchFieldController::class)->names('api.research-fields');

        // Lecturer Educations
        Route::get('/lecturers/{lecturer_id}/educations/details', [LecturerEducationController::class, 'show'])->name('api.lecturers.educations.show');
        Route::put('/lecturers/{lecturer_id}/educations/update', [LecturerEducationController::class, 'update'])->name('api.lecturers.educations.update');
        Route::delete('/lecturers/{lecturer_id}/educations/{education_id}', [LecturerEducationController::class, 'destroy'])->name('api.lecturers.educations.destroy');

        // Lecturer Experiences
        Route::get('/lecturers/{lecturer_id}/experiences/details', [LecturerExperienceController::class, 'show'])->name('api.lecturers.experiences.show');
        Route::put('/lecturers/{lecturer_id}/experiences/update', [LecturerExperienceController::class, 'update'])->name('api.lecturers.experiences.update');
        Route::delete('/lecturers/{lecturer_id}/experiences/{experience_id}', [LecturerExperienceController::class, 'destroy'])->name('api.lecturers.experiences.destroy');

        // Lecturer Research Fields
        Route::get('/lecturers/{lecturer_id}/research-fields/details', [LecturerResearchFieldController::class, 'show'])->name('api.lecturers.research-fields.show');
        Route::put('/lecturers/{lecturer_id}/research-fields/update', [LecturerResearchFieldController::class, 'update'])->name('api.lecturers.research-fields.update');
        Route::delete('/lecturers/{lecturer_id}/research-fields/{experience_id}', [LecturerResearchFieldController::class, 'destroy'])->name('api.lecturers.research-fields.destroy');
    });

    require __DIR__.'/auth.php';
});
