<?php

namespace App\Http\Controllers\Exams;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Submission\Submission;
use App\Services\Exams\ProposalSeminarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProposalSeminarController extends Controller
{
    public function __construct(protected ProposalSeminarService $service, protected ApiResponseHelper $apiResponseHelper, protected ApiResponseClass $apiResponseClass) {}

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Submission $submission, Request $request): JsonResponse
    {
        try {
            $exam = $this->service->create($submission->id);
            return $this->apiResponseClass->sendResponse(201, 'Proposal Seminar created successfully', $exam->toArray());
        } catch (\Exception $e) {
            return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Submission $submission, Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'exam_date' => ['nullable', 'date'],
                'exam_time' => ['nullable', 'date_format:H:i'],
                'exam_place' => ['nullable', 'string', 'max:255'],
            ]);

            $exam = $this->service->update($submission->id, $validatedData);

            return $this->apiResponseClass->sendResponse(200, 'Proposal Seminar updated successfully', $exam->toArray());
        } catch (ResourceNotFoundException $e) {
            return $this->apiResponseClass->sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }
}
