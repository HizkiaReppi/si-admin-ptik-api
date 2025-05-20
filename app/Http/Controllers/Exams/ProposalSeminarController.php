<?php

namespace App\Http\Controllers\Exams;

use App\Classes\ApiResponseClass;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Exams\ProposalSeminarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProposalSeminarController extends Controller
{
    public function __construct(
        protected ProposalSeminarService $service, 
        protected ApiResponseHelper $apiResponseHelper, 
        protected ApiResponseClass $apiResponseClass
    ) {}

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

        $exams = $this->service->getAll($filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($exams);
        $exams = $exams->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Proposal Seminar retrieved successfully', $exams, $pagination);
    }
}
