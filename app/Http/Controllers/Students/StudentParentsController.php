<?php

namespace App\Http\Controllers\Students;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Students\UpdateStudentParentsRequest;
use App\Services\Students\StudentParentsService;
use Illuminate\Http\JsonResponse;

class StudentParentsController extends Controller
{
    public function __construct(
        protected StudentParentsService $studentParentsService
    ) { }

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
        $results = $this->studentParentsService->getByStudentId($studentId);

        return ApiResponseClass::sendResponse(200, 'Student parents information retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentParentsRequest $request, string $studentId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->studentParentsService->update($validatedData, $studentId);

            return ApiResponseClass::sendResponse(200, 'Student parents information updated successfully', $results->toArray());
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
            $this->studentParentsService->delete($studentId);

            return ApiResponseClass::sendResponse(200, 'Student parents information deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
