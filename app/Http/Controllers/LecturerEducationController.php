<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturer;
use App\Http\Requests\UpdateLecturerEducationsRequest;
use App\Services\LecturerEducationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LecturerEducationController extends Controller
{
    private LecturerEducationService $lecturerEducationService;

    public function __construct(LecturerEducationService $lecturerEducationService)
    {
        $this->lecturerEducationService = $lecturerEducationService;
    }

    /**
     * Display a paginated list of lecturers with optional search and filters.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $lecturerId
     * @return JsonResponse
     */
    public function show(string $lecturerId): JsonResponse
    {
        $results = $this->lecturerEducationService->getByLecturerId($lecturerId);

        return ApiResponseClass::sendResponse(200, 'Lecturers educations retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerEducationsRequest $request, string $lecturerId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->lecturerEducationService->update($validatedData, $lecturerId);

            return ApiResponseClass::sendResponse(200, 'Lecturer educations updated successfully', $results->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
