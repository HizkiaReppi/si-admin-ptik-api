<?php

namespace App\Http\Controllers\Students;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
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

        return ApiResponseClass::sendResponseWithPagination(200, 'Students retrieved successfully', $students, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo');
            }

            $student = $this->studentService->create($validatedData);

            return ApiResponseClass::sendResponse(201, 'Student created successfully', $student->toArray());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'Failed to create student:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): JsonResponse
    {
        try {
            $student = $this->studentService->getById($student->id, [
                'user',
                'firstSupervisor',
                'secondSupervisor',
                'information',
                'addresses',
                'parents'
            ]);

            return ApiResponseClass::sendResponse(200, 'Student retrieved successfully', $student->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo');
            }

            $student = $this->studentService->update($validatedData, $student->id);

            return ApiResponseClass::sendResponse(200, 'Student updated successfully', $student->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): JsonResponse
    {
        try {
            $this->studentService->delete($student->id);
            return ApiResponseClass::sendResponse(200, 'Student deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }
}
