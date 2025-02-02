<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\StoreResearchFieldRequest;
use App\Http\Requests\UpdateResearchFieldRequest;
use App\Models\Lecturers\ResearchField;
use App\Services\ResearchFieldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResearchFieldController extends Controller
{
    private ResearchFieldService $researchFieldService;
    private ApiResponseHelper $apiResponseHelper;

    public function __construct(ResearchFieldService $researchFieldService, ApiResponseHelper $apiResponseHelper)
    {
        $this->researchFieldService = $researchFieldService;
        $this->apiResponseHelper = $apiResponseHelper;
    }

    /**
     * Display a paginated list of research fields with optional search and filters.
     *
     * @param Illuminate\Http\Request $request
     * @return JsonResponse
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

        $researchFields = $this->researchFieldService->getAll($filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($researchFields);
        $researchFields = $researchFields->items();

        return ApiResponseClass::sendResponseWithPagination(200, 'Research fields retrieved successfully', $researchFields, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResearchFieldRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $researchField = $this->researchFieldService->create($validatedData);

            return ApiResponseClass::sendResponse(201, 'Research Field created successfully', $researchField->toArray());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'Failed to create Research Field:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchField $researchField): JsonResponse
    {
        try {
            $researchField = $this->researchFieldService->getById($researchField->id);
            return ApiResponseClass::sendResponse(200, 'Research Field retrieved successfully', $researchField->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResearchFieldRequest $request, ResearchField $researchField): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $researchField->update($validatedData);

            return ApiResponseClass::sendResponse(200, 'Research Field updated successfully', $researchField->toArray());
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchField $researchField): JsonResponse
    {
        try {
            $researchField->delete();
            return ApiResponseClass::sendResponse(200, 'Research Field deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return ApiResponseClass::sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, 'An error occurred. Please try again later.');
        }
    }
}
