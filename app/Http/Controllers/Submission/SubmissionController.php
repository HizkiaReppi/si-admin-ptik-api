<?php

namespace App\Http\Controllers\Submission;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Models\Category;
use App\Models\Submission\Submission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Services\Submission\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
        protected ApiResponseHelper $apiResponseHelper,
        protected ApiResponseClass $apiResponseClass
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request): JsonResponse
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

        $submissions = $this->submissionService->getAll($category->slug, $filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($submissions);
        $submissions = $submissions->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Submissions retrieved successfully', $submissions, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Submission $submission)
    {
        try {
            $category = $this->submissionService->getById($category->slug, $submission->id);
            return $this->apiResponseClass->sendResponse(200, 'Submission retrieved successfully', $category->toArray());
        } catch (ResourceNotFoundException $e) {
           if($e->getCode() === 409) {
                return $this->apiResponseClass->sendError(409, $e->getMessage());
            } else {
                return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }

    /**
     * Verify a submission and generate document number.
     */
    public function verify(Request $request, string $categorySlug, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:in_review,faculty_review,completed',
        ]);

        $submission = $this->submissionService->verifySubmission(
            $categorySlug,
            $id,
            $request->status,
            $request->reviewer_name
        );

        if (!$submission) {
            return response()->json(['message' => 'Submission not found'], 404);
        }

        return response()->json(['message' => 'Submission verified successfully', 'data' => $submission]);
    }

    /**
     * Reject a submission with a reason.
     */
    public function reject(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $submission = $this->submissionService->rejectSubmission(
            $id,
            $request->reviewer_name,
            $request->reason
        );

        if (!$submission) {
            return response()->json(['message' => 'Submission not found'], 404);
        }

        return response()->json(['message' => 'Submission rejected successfully', 'data' => $submission]);
    }
}
