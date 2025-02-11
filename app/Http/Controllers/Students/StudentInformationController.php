<?php

namespace App\Http\Controllers\Students;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Students\UpdateStudentInformationRequest;
use App\Services\Students\StudentInformationService;
use Illuminate\Http\JsonResponse;

class StudentInformationController extends Controller
{
    private StudentInformationService $studentInformationService;

    public function __construct(StudentInformationService $studentInformationService)
    {
        $this->studentInformationService = $studentInformationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $studentId
     * @return JsonResponse
     */
    public function show(string $studentId): JsonResponse
    {
        $results = $this->studentInformationService->getByStudentId($studentId);

        return ApiResponseClass::sendResponse(200, 'Student information retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentInformationRequest $request, string $studentId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->studentInformationService->update($validatedData, $studentId);

            return ApiResponseClass::sendResponse(200, 'Student information updated successfully', $results->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $studentId): JsonResponse
    {
        try {
            $this->studentInformationService->delete($studentId);

            return ApiResponseClass::sendResponse(200, 'Student information deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
