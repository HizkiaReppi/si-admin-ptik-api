<?php

namespace App\Http\Controllers\Exams;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Helpers\StudentHelper;
use App\Helpers\TextFormattingHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exam;
use App\Models\HeadOfDepartment;
use App\Models\Submission\Submission;
use App\Services\Exams\ExamsService;
use App\Services\Templates\TemplateMergeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamsController extends Controller
{
    public function __construct(
        protected ExamsService $service, 
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

        $exams = $this->service->getAll($category->slug, $filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($exams);
        $exams = $exams->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Exams retrieved successfully', $exams, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Category $category, Submission $submission, Request $request): JsonResponse
    {
        try {
            $exam = $this->service->create($submission->id);
            return $this->apiResponseClass->sendResponse(201, 'Exams created successfully', $exam->toArray());
        } catch (\Exception $e) {
            return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category, Submission $submission, Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'exam_date' => ['nullable', 'date'],
                'exam_time' => ['nullable', 'date_format:H:i'],
                'exam_place' => ['nullable', 'string', 'max:255'],
            ]);

            $exam = $this->service->update($submission->id, $validatedData);

            return $this->apiResponseClass->sendResponse(200, 'Exams updated successfully', $exam->toArray());
        } catch (ResourceNotFoundException $e) {
            return $this->apiResponseClass->sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.', [$e->getMessage()]);
        }
    }

    public function generateDocument(
        Category $category,
        Exam $exam,
        string $documentType,
        TemplateMergeService $templateMergeService,
        TextFormattingHelper $textFormattingHelper,
    ) {
        $validDocumentTypes = ['undangan-ujian', 'berita-acara-ujian'];
        if (!in_array($documentType, $validDocumentTypes)) {
            return response()->json(['message' => 'Invalid document type'], 400);
        }
        
        $exam->load([
            'submission', 
            'submission.student', 
            'submission.student.user', 
            'submission.student.firstSupervisor', 
            'submission.student.secondSupervisor', 
            'submission.files',
            'submission.document',
            'submission.examiners.examiner',
            'submission.examiners.examiner.user'
        ]);
        $thesisTitle = $exam->submission->files->where('requirement.name', 'Judul Skripsi')->first()?->file_path ?? '-';

        $headOfDepartment = HeadOfDepartment::where('role', 'kajur')->with('lecturer.user')->first();
        
        switch ($category->slug) {
            case 'sk-seminar-proposal':
                switch ($documentType) {
                    case 'undangan-ujian':
                        $data = [
                            'documentNumber' => $exam->document->document_number,
                            'studentName' => $exam->submission->student->user->name,
                            'studentSupervisor' => $exam->submission->student->firstSupervisor?->full_name ?? '-',
                            'examiner1' => $exam->submission->examiners[0]->examiner->full_name ?? '-',
                            'examiner2' => $exam->submission->examiners[1]->examiner->full_name ?? '-',
                            'examiner3' => $exam->submission->examiners[2]->examiner->full_name ?? '-',
                            'documentDate' => $exam->document->document_date,
                            'headOfDepartmentName' => $headOfDepartment?->lecturer->full_name ?? '-',
                            'headOfDepartmentNip' => $textFormattingHelper->formatNIP($headOfDepartment?->lecturer->nip) ?? '-',
                            'examDate' => $exam->exam_date,
                            'examTime' => $exam->exam_time,
                            'examPlace' => $exam->exam_place,
                        ];
                        $path = $templateMergeService->generateUndanganSeminarProposal($data);
                        return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
        
                    case 'berita-acara-ujian':
                        $data = [
                            'studentName' => $exam->submission->student->user->name,
                            'studentNim' => $textFormattingHelper->formatNIM($exam->submission->student->nim),
                            'thesisTitle' => $thesisTitle ?? '-',
                            'examDate' => $exam->exam_date ? explode(',', $exam->exam_date)[1] : '-',
                            'examDay' => $exam->exam_date ? str_pad(explode(',', $exam->exam_date)[0], 2, '0', STR_PAD_LEFT) : '-',
                        ];
                        $path = $templateMergeService->generateBeritaAcaraSeminarProposal($data);
                        return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
        
                    default:
                        return response()->json(['message' => 'Invalid document type'], 400);
                }
        
            case 'sk-ujian-hasil-penelitian':
                switch ($documentType) {
                    case 'undangan-ujian':
                        $data = [
                            'documentNumber' => $exam->document->document_number,
                            'studentName' => $exam->submission->student->user->name,
                            'studentSupervisor1' => $exam->submission->student->firstSupervisor?->full_name ?? '-',
                            'studentSupervisor2' => $exam->submission->student->secondSupervisor?->full_name ?? '-',
                            'examiner1' => $exam->submission->examiners[0]->examiner->full_name ?? '-',
                            'examiner2' => $exam->submission->examiners[1]->examiner->full_name ?? '-',
                            'examiner3' => $exam->submission->examiners[2]->examiner->full_name ?? '-',
                            'documentDate' => $exam->document->document_date,
                            'headOfDepartmentName' => $headOfDepartment?->lecturer->full_name ?? '-',
                            'headOfDepartmentNip' => $textFormattingHelper->formatNIP($headOfDepartment?->lecturer->nip) ?? '-',
                            'examDate' => $exam->exam_date,
                            'examTime' => $exam->exam_time,
                            'examPlace' => $exam->exam_place,
                        ];
                        $path = $templateMergeService->generateUndanganUjianHasilPenelitian($data);
                        return response()->download(storage_path("app/public/{$path}"))->deleteFileAfterSend();
        
                    default:
                        return response()->json(['message' => 'Invalid document type'], 400);
                }
        
            default:
                return response()->json(['message' => 'Invalid category'], 400);
        }
        
    }
}
