<?php

namespace App\Http\Controllers\HeadOfDepartment;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeadOfDepartment\StoreHeadOfDepartmentRequest;
use App\Http\Requests\HeadOfDepartment\UpdateHeadOfDepartmentRequest;
use App\Models\HeadOfDepartment;
use App\Services\HeadOfDepartment\HeadOfDepartmentService;
use Illuminate\Http\JsonResponse;

class HeadOfDepartmentController extends Controller
{
    public function __construct(
        protected HeadOfDepartmentService $headOfDepartmentService,
        protected ApiResponseClass $apiResponseClass,
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $headOfDepartments = $this->headOfDepartmentService->getAll();
        return $this->apiResponseClass->sendResponse(200, 'Head Of Departments retrieved successfully', $headOfDepartments->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHeadOfDepartmentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $headOfDepartment = $this->headOfDepartmentService->create($validatedData);

            return ApiResponseClass::sendResponse(201, 'Head Of Department created successfully', $headOfDepartment->toArray());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'Failed to create Head Of Department:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HeadOfDepartment $headOfDepartment): JsonResponse
    {
        try {
            $headOfDepartment = $this->headOfDepartmentService->getById($headOfDepartment->id);
            return ApiResponseClass::sendResponse(200, 'Head Of Department retrieved successfully', $headOfDepartment->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHeadOfDepartmentRequest $request, HeadOfDepartment $headOfDepartment): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $headOfDepartment = $this->headOfDepartmentService->update($validatedData, $headOfDepartment->id);

            return ApiResponseClass::sendResponse(200, 'Head Of Department updated successfully', $headOfDepartment->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeadOfDepartment $headOfDepartment): JsonResponse
    {
        try {
            $this->headOfDepartmentService->delete($headOfDepartment->id);
            return ApiResponseClass::sendResponse(200, 'Head Of Department deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }
}
