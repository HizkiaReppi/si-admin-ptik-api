<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\User;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService,
        protected ApiResponseClass $apiResponseClass,
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administrators = $this->adminService->getAll();
        return $this->apiResponseClass->sendResponse(200, 'Administrators retrieved successfully', $administrators->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $administrator = $this->adminService->create($validatedData);
            return ApiResponseClass::sendResponse(201, 'Administrator created successfully', $administrator->toArray());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'Failed to create Administrator:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $administrator)
    {
        try {
            $administrator = $this->adminService->getById($administrator->id);
            return ApiResponseClass::sendResponse(200, 'Administrator retrieved successfully', $administrator->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, User $administrator)
    {
        $validatedData = $request->validated();
        try {
            $admin = $this->adminService->update($validatedData, $administrator->id);
            return ApiResponseClass::sendResponse(200, 'Administrator updated successfully', $admin->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $administrator)
    {
        try {
            $this->adminService->delete($administrator->id);
            return ApiResponseClass::sendResponse(200, 'Administrator deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }
}
