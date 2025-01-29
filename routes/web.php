<?php

use App\Http\Controllers\LecturerController;
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
    });

    require __DIR__.'/auth.php';
});
