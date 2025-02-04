<?php

namespace App\Http\Controllers\Students;

use App\Classes\ApiResponseClass;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\Students\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private StudentService $studentService;
    private ApiResponseHelper $apiResponseHelper;

    public function __construct(StudentService $studentService, ApiResponseHelper $apiResponseHelper)
    {
        $this->studentService = $studentService;
        $this->apiResponseHelper = $apiResponseHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->only('search');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', null);
        $order = $request->input('order');

        $filters = [
            'search' => $search['search'] ?? null,
            'sortBy' => $sortBy,
            'order' => $order,
        ];

        $students = $this->studentService->getAll($filters, ['user'], (int) $perPage);
        $pagination = $this->apiResponseHelper->generatePagination($students);
        $students = $students->items();

        return ApiResponseClass::sendResponse(200, 'Method not implemented yet');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $students = $this->studentService->create($request->validated());
        return ApiResponseClass::sendResponse(200, 'Method not implemented yet');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): JsonResponse
    {
        $student = $this->studentService->getById($student->id);
        return ApiResponseClass::sendResponse(200, 'Method not implemented yet');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $student = $this->studentService->update($student->id, $request->validated());
        return ApiResponseClass::sendResponse(200, 'Method not implemented yet');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): JsonResponse
    {
        $this->studentService->delete($student->id);
        return ApiResponseClass::sendResponse(200, 'Method not implemented yet');
    }
}
