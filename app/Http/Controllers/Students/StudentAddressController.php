<?php

namespace App\Http\Controllers\Students;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Students\UpdateStudentAddressRequest;
use App\Services\Students\StudentAddressService;
use Illuminate\Http\JsonResponse;

class StudentAddressController extends Controller
{
    public function __construct(
        protected StudentAddressService $studentAddressService
    ) {}

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
        $results = $this->studentAddressService->getByStudentId($studentId);

        return ApiResponseClass::sendResponse(200, 'Student address retrieved successfully', $results->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentAddressRequest $request, string $studentId): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $results = $this->studentAddressService->update($validatedData, $studentId);

            return ApiResponseClass::sendResponse(200, 'Student address updated successfully', $results->toArray());
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
            $this->studentAddressService->delete($studentId);

            return ApiResponseClass::sendResponse(200, 'Student address deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError(404, $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
