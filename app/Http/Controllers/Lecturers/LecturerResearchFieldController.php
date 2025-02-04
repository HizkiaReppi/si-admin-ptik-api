<?php

namespace App\Http\Controllers\Lecturers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturers\UpdateLecturerResearchFieldRequest;
use App\Services\LecturerResearchFieldService;
use Illuminate\Http\JsonResponse;

class LecturerResearchFieldController extends Controller
{
    private LecturerResearchFieldService $lecturerResearchFieldService;

    public function __construct(LecturerResearchFieldService $lecturerResearchFieldService)
    {
        $this->lecturerResearchFieldService = $lecturerResearchFieldService;
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
        $results = $this->lecturerResearchFieldService->getByLecturerId($lecturerId);

        return ApiResponseClass::sendResponse(200, 'Lecturers Research Fields retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerResearchFieldRequest $request, string $lecturerId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->lecturerResearchFieldService->update($validatedData, $lecturerId);

            return ApiResponseClass::sendResponse(200, 'Lecturer Research Fields updated successfully', $results->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $lecturerId, string $researchFieldId): JsonResponse
    {
        try {
            $this->lecturerResearchFieldService->delete($lecturerId, $researchFieldId);

            return ApiResponseClass::sendResponse(200, 'Lecturer Research Field deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
