<?php

namespace App\Http\Controllers\Submission;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Helpers\StudentHelper;
use App\Helpers\TextFormattingHelper;
use App\Models\Category;
use App\Models\Submission\Submission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\Submission\UpdateSubmissionStatusRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Models\HeadOfDepartment;
use App\Services\Submission\SubmissionService;
use App\Services\Templates\TemplateMergeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function __construct(protected SubmissionService $submissionService, protected ApiResponseHelper $apiResponseHelper, protected ApiResponseClass $apiResponseClass) {}

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
            if ($e->getCode() === 409) {
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
    /**
     * Update submission status.
     */
    public function updateStatus(UpdateSubmissionStatusRequest $request, Category $category, Submission $submission): JsonResponse
    {
        try {
            $submission = $this->submissionService->verify($category->slug, $submission->id, $request->status, Auth::user()->name, $request->reason, $request->examiners, $request->supervisors);

            return response()->json(
                [
                    'data' => $submission,
                    'message' => 'Submission status updated successfully.',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'errors' => ['general' => [$e->getMessage()]],
                ],
                400,
            );
        }
    }

    public function generateDocument(
        Category $category, 
        Submission $submission, 
        TemplateMergeService $templateMergeService, 
        TextFormattingHelper $textFormattingHelper, 
        StudentHelper $studentHelper
    ) {
        $submission->load(['student.user', 'student.firstSupervisor.user', 'student.secondSupervisor.user', 'examiners.examiner.user', 'supervisors.supervisor.user']);

        $headOfDepartment = HeadOfDepartment::where('role', 'kajur')->with('lecturer.user')->first();

        if ($category->slug === 'sk-seminar-proposal') {
            $data = [
                'documentNumber' => $submission->document_number,
                'studentName' => $submission->student->user->name,
                'studentNim' => $submission->student->nim,
                'studentSemester' => $submission->student->entry_year ? +$studentHelper->getCurrentSemesterStudent($submission->student->entry_year) : '-',
                'thesisTitle' => $submission->thesis_title ?? '-',
                'studentSupervisor' => $submission->student->firstSupervisor?->full_name ?? '-',
                'examiner1' => $submission->examiners[0]->examiner->full_name ?? '-',
                'examiner2' => $submission->examiners[1]->examiner->full_name ?? '-',
                'examiner3' => $submission->examiners[2]->examiner->full_name ?? '-',
                'documentDate' => $submission->document_date,
                'headOfDepartmentName' => $headOfDepartment?->lecturer->full_name ?? '-',
                'headOfDepartmentNip' => $textFormattingHelper->formatNIP($headOfDepartment?->lecturer->nip) ?? '-',
            ];

            $path = $templateMergeService->generateSuratSeminarProposal($data);

            return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
        }
    }
}
