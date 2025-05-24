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
    public function __construct(
        protected SubmissionService $submissionService, 
        protected ApiResponseHelper $apiResponseHelper, 
        protected ApiResponseClass $apiResponseClass
    ) {}

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'uuid'],
            'category_slug' => ['required', 'string'],
            'files' => ['required', 'array'],
            'files.*.requirement_id' => ['required', 'string'],
            'files.*.text' => ['nullable', 'string'],
            'files.*.file' => ['nullable', 'file'],
        ]);

        $submission = $this->submissionService->createSubmissionWithFiles($validated);

        return $this->apiResponseClass->sendResponse(200, 'Submission created successfully', $submission->toArray());
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
        $submission->load(['student.user', 'student.information', 'student.firstSupervisor.user', 'student.secondSupervisor.user', 'examiners.examiner.user', 'supervisors.supervisor.user', 'files.requirement']);

        $headOfDepartment = HeadOfDepartment::where('role', 'kajur')->with('lecturer.user')->first();
        $thesisTitle = $submission->files->where('requirement.name', 'Judul Skripsi')->first()?->file_path ?? '-';
        
        switch ($category->slug) {
            case 'sk-seminar-proposal':
                $data = [
                    'documentNumber' => $submission->document_number,
                    'studentName' => $submission->student->user->name,
                    'studentNim' => $textFormattingHelper->formatNIM($submission->student->nim),
                    'studentSemester' => $submission->student->entry_year ? +$studentHelper->getCurrentSemesterStudent($submission->student->entry_year) : '-',
                    'thesisTitle' => $thesisTitle ?? '-',
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
                break;
            case 'sk-ujian-hasil-penelitian':
                $data = [
                    'documentNumber' => $submission->document_number,
                    'studentName' => $submission->student->user->name,
                    'studentNim' => $textFormattingHelper->formatNIM($submission->student->nim),
                    'studentSemester' => $submission->student->entry_year ? +$studentHelper->getCurrentSemesterStudent($submission->student->entry_year) : '-',
                    'thesisTitle' => $thesisTitle ?? '-',
                    'studentSupervisor1' => $submission->student->firstSupervisor?->full_name ?? '-',
                    'studentSupervisor2' => $submission->student->secondSupervisor?->full_name ?? '-',
                    'examiner1' => $submission->examiners[0]->examiner->full_name ?? '-',
                    'examiner2' => $submission->examiners[1]->examiner->full_name ?? '-',
                    'examiner3' => $submission->examiners[2]->examiner->full_name ?? '-',
                    'documentDate' => $submission->document_date,
                    'headOfDepartmentName' => $headOfDepartment?->lecturer->full_name ?? '-',
                    'headOfDepartmentNip' => $textFormattingHelper->formatNIP($headOfDepartment?->lecturer->nip) ?? '-',
                ];
                $path = $templateMergeService->generateSuratSeminarHasil($data);
                return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
                break;
            case 'permohonan-ujian-komprehensif':
                $missingFields = [];

                if (!$submission->document_number) $missingFields[] = 'Nomor Dokumen';
                if (!$submission->student->user->name) $missingFields[] = 'Nama Mahasiswa';
                if ($submission->student->information->place_of_birth == null) $missingFields[] = 'Tempat Lahir';
                if ($submission->student->information->date_of_birth == null) $missingFields[] = 'Tanggal Lahir';
                if (!$submission->student->nim) $missingFields[] = 'NIM';
                if (!$submission->student->class) $missingFields[] = 'Kelas';
                if (!$submission->student->entry_year) $missingFields[] = 'Tahun Masuk';
                if (!$thesisTitle) $missingFields[] = 'Judul Skripsi';
                if (!$submission->student->firstSupervisor) $missingFields[] = 'Pembimbing 1';
                if (!$submission->student->secondSupervisor) $missingFields[] = 'Pembimbing 2';

                for ($i = 0; $i < 5; $i++) {
                    if (empty($submission->examiners[$i]) || empty($submission->examiners[$i]->examiner->full_name)) {
                        $missingFields[] = "Penguji " . ($i + 1);
                    }
                }

                if (!$submission->document_date) $missingFields[] = 'Tanggal Dokumen';
                if (!$headOfDepartment?->lecturer->full_name) $missingFields[] = 'Nama Ketua Jurusan';
                if (!$headOfDepartment?->lecturer->nip) $missingFields[] = 'NIP Ketua Jurusan';

                if (count($missingFields) > 0) {
                    return response()->json([
                        'message' => 'Data tidak lengkap: ' . implode(', ', $missingFields)
                    ], 400);
                }

                $data = [
                    'documentNumber' => $submission->document_number,
                    'studentName' => $submission->student->user->name,
                    'studentPlaceDateOfBirth' => $submission->student->information->place_of_birth . ', ' . \Carbon\Carbon::parse($submission->student->information->date_of_birth)->translatedFormat('d F Y'),
                    'studentNim' => $textFormattingHelper->formatNIM($submission->student->nim),
                    'studentClass' => ucfirst($submission->student->class),
                    'studentEntryYear' => $submission->student->entry_year,
                    'thesisTitle' => $thesisTitle ?? '-',
                    'studentSupervisor1' => $submission->student->firstSupervisor->full_name,
                    'studentSupervisor2' => $submission->student->secondSupervisor->full_name,
                    'examiner1' => $submission->examiners[0]->examiner->full_name,
                    'examiner2' => $submission->examiners[1]->examiner->full_name,
                    'examiner3' => $submission->examiners[2]->examiner->full_name,
                    'examiner4' => $submission->examiners[3]->examiner->full_name,
                    'examiner5' => $submission->examiners[4]->examiner->full_name,
                    'documentDate' => $submission->document_date,
                    'headOfDepartmentName' => $headOfDepartment->lecturer->full_name,
                    'headOfDepartmentNip' => $textFormattingHelper->formatNIP($headOfDepartment->lecturer->nip),
                ];

                $path = $templateMergeService->generateSuratUjianKomprehensif($data);
                return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
                break;
            default:
                return response()->json(['message' => 'Invalid category'], 400);
                break;
        }
    }

    public function getAllCount(): JsonResponse
    {
        $count = $this->submissionService->getAllCount();
        return $this->apiResponseClass->sendResponse(200, 'All Submission counts retrieved successfully', ['count' => $count]);
    }

    public function getAllByUserId(string $userId, Request $request): JsonResponse
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

        $submissions = $this->submissionService->getAllByUserId($userId, $filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($submissions);
        $submissions = $submissions->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Submissions retrieved successfully', $submissions, $pagination);
    }

    public function getAllByStatus(Request $request): JsonResponse
    {
        $search = $request->only('search');
        $status = $request->input('status', 'submitted');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', null);
        $order = $request->input('order');

        $filters = [
            'search' => $search['search'] ?? null,
            'sortBy' => $sortBy,
            'order' => $order,
        ];

        $submissions = $this->submissionService->getAllByStatus($status, $filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($submissions);
        $submissions = $submissions->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Submissions retrieved successfully', $submissions, $pagination);
    }
}
