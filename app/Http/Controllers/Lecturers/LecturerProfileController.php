<?php

namespace App\Http\Controllers\Lecturers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturers\UpdateLecturerProfilesRequest;
use App\Services\Lecturers\LecturerProfileService;
use Illuminate\Http\JsonResponse;

class LecturerProfileController extends Controller
{
    private LecturerProfileService $lecturerProfileService;

    public function __construct(LecturerProfileService $lecturerProfileService)
    {
        $this->lecturerProfileService = $lecturerProfileService;
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
        $results = $this->lecturerProfileService->getByLecturerId($lecturerId);

        return ApiResponseClass::sendResponse(200, 'Lecturers external profiles retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerProfilesRequest $request, string $lecturerId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->lecturerProfileService->update($validatedData, $lecturerId);

            return ApiResponseClass::sendResponse(200, 'Lecturer external profiles updated successfully', $results->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $lecturerId, string $profileId): JsonResponse
    {
        try {
            $this->lecturerProfileService->delete($lecturerId, $profileId);

            return ApiResponseClass::sendResponse(200, 'Lecturer external profiles deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
