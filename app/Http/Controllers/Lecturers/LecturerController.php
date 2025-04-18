<?php

namespace App\Http\Controllers\Lecturers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturers\StoreLecturerRequest;
use App\Http\Requests\Lecturers\UpdateLecturerRequest;
use App\Models\Lecturer;
use App\Services\Lecturers\LecturerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    private LecturerService $lecturerService;
    private ApiResponseHelper $apiResponseHelper;

    public function __construct(LecturerService $lecturerService, ApiResponseHelper $apiResponseHelper)
    {
        $this->lecturerService = $lecturerService;
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

        $lecturers = $this->lecturerService->getLecturers($filters, ['user', 'researchFields'], (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($lecturers);
        $lecturers = $lecturers->items();

        return ApiResponseClass::sendResponseWithPagination(200, 'Lecturers retrieved successfully', $lecturers, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLecturerRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo');
            }

            $lecturer = $this->lecturerService->createLecturer($validatedData);

            return ApiResponseClass::sendResponse(201, 'Lecturer created successfully', $lecturer->toArray());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'Failed to create lecturer:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecturer $lecturer): JsonResponse
    {
        try {
            $lecturer = $this->lecturerService->getLecturerById($lecturer->id, [
                'user',
                'educations',
                'experiences',
                'researchFields',
                'profiles',
                'firstSupervisedStudents',
                'secondSupervisedStudents',
            ]);

            return ApiResponseClass::sendResponse(200, 'Lecturer retrieved successfully', $lecturer->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerRequest $request, string $lecturer_id): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo');
            }

            $lecturer = $this->lecturerService->updateLecturer($validatedData, $lecturer_id);

            return ApiResponseClass::sendResponse(200, 'Lecturer updated successfully', $lecturer->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer): JsonResponse
    {
        try {
            $this->lecturerService->deleteLecturer($lecturer->id);
            return ApiResponseClass::sendResponse(200, 'Lecturer deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }
}
