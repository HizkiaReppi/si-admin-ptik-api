<?php

namespace App\Http\Controllers\Lecturers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturers\UpdateLecturerEducationsRequest;
use App\Services\Lecturers\LecturerEducationService;
use Illuminate\Http\JsonResponse;

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $lecturerId, string $educationId): JsonResponse
    {
        try {
            $this->lecturerEducationService->delete($lecturerId, $educationId);

            return ApiResponseClass::sendResponse(200, 'Lecturer education deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
