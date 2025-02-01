<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Helpers\ApiResponseHelper;
use App\Models\Lecturers\ResearchField;
use App\Services\ResearchFieldService;
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
    public function index(Request $request)
    {
        $search = $request->only('search');
        $perPage = $request->input('per_page', 10);

        $filters = [
            'search' => $search['search'] ?? null,
        ];

        $researchFields = $this->researchFieldService->getAll($filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($researchFields);
        $researchFields = $researchFields->items();

        return ApiResponseClass::sendResponseWithPagination(200, 'Lecturers retrieved successfully', $researchFields, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResearchField $researchField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchField $researchField)
    {
        //
    }
}
