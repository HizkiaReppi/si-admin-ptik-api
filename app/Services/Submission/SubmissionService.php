<?php

namespace App\Services\Submission;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Submission\SubmissionRepository;
use App\Models\Submission\Submission;
use App\Repositories\Exams\ExamsRepository;
use App\Repositories\Submission\CategoryRepository;
use App\Services\Students\StudentService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class SubmissionService
{
    public function __construct(
        protected SubmissionRepository $repository,
        protected CategoryRepository $categoryRepository,
        protected ExamsRepository $examsRepository,
        protected SubmissionFileService $fileService,
        protected StudentService $studentService,
    ) {}

    public function getAll(string $categorySlug, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAll($categorySlug, $filters, $perPage);
    }

    public function getById(string $categorySlug, string $id): ?Submission
    {
        return $this->repository->getById($categorySlug, $id);
    }

    public function createSubmissionWithFiles(array $data)
    {
        $categoryId = $this->categoryRepository->getIdBySlug($data['category_slug']);
        $student = $this->studentService->getByUserId($data['user_id']);

        $submission = $this->repository->create([
            'student_id' => $student->id,
            'category_id' => $categoryId,
            'status' => 'submitted',
        ]);

        $this->fileService->storeFiles($data['files'], $submission->id);

        return $submission;
    }

    /**
     * Verify or update submission status.
     *
     * @throws Exception
     */
    public function verify(
        string $categorySlug, 
        string $submissionId, 
        string $status, 
        string $reviewerName, 
        ?string $reason = null, 
        ?array $examiners = null, 
        ?array $supervisors = null,
        ?string $generatedFilePath = null,
        string $generatedDocumentNumber,
        string $generatedDocumentDate,
    ): Submission {
        DB::beginTransaction();

        try {
            $submission = $this->repository->getById($categorySlug, $submissionId);

            if (!$submission) {
                throw new Exception('Pengajuan tidak ditemukan.');
            }

            $document = $submission->document;
            if ($document) {
                if ($document->document_number !== null) {
                    $documentNumber = $document->document_number;
                } 
                
                if ($document->document_date !== null) {
                    $documentDate = $document->document_date;
                }
            } 
            else {
                $documentNumber = null;
                $documentDate = null;
            }

            if ($status === 'faculty_review') {
                $documentNumberFormat = env('DOCUMENT_NUMBER_FORMAT', '');

                $currentYear = date('Y');
                $documentNumber = $this->repository->getLastDocumentNumber() . "/{$documentNumberFormat}/{$currentYear}";

                Carbon::setLocale('id');
                $documentDate = Carbon::now()->translatedFormat('d F Y');
            }

            $submission = $this->repository->updateStatus($submission, $status, $reviewerName, $reason, $documentNumber, $documentDate);

            if ($examiners && $status === 'faculty_review') {
                $this->repository->addExaminers($submission, $examiners);
            }

            if ($supervisors && $status === 'faculty_review' && $categorySlug === 'permohonan-sk-pembimbing-skripsi') {
                $this->repository->addSupervisors($submission, $supervisors);
            }

            if ($status === 'completed' && ($categorySlug === 'sk-seminar-proposal' || $categorySlug === 'sk-ujian-hasil-penelitian')) {
                Log::info('generatedDocumentNumber', [
                    $generatedDocumentNumber
                ]);
                Log::info('generatedDocumentDate', [
                    $generatedDocumentDate
                ]);
                
                $this->repository->updateGeneratedSubmission($submission, $generatedFilePath, $generatedDocumentNumber, $generatedDocumentDate);
                $this->examsRepository->create($submission->id);
            }

            // if ($status !== 'completed') {
            //     try {
            //         $this->examsRepository->delete($submission->id);
            //     } catch (ResourceNotFoundException $e) {}
            // }

            DB::commit();

            Log::info('Submission status updated', [
                'submission_id' => $submissionId,
                'status' => $status,
                'reviewer' => $reviewerName,
            ]);

            return $submission;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update submission status', [
                'submission_id' => $submissionId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Gagal memperbarui status pengajuan.');
        }
    }

    /**
     * Reject a submission with a reason.
     *
     * @throws Exception
     */
    public function reject(Submission $submission, string $reason): Submission
    {
        try {
            $submission = $this->repository->updateStatus($submission, 'rejected', null, $reason);

            Log::info('Submission rejected', [
                'submission_id' => $submission->id,
                'reason' => $reason,
            ]);

            return $submission;
        } catch (Exception $e) {
            Log::error('Failed to reject submission', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Gagal menolak pengajuan.');
        }
    }

    public function getAllCount(): int
    {
        return $this->repository->allSubmissionsCount();
    }

    public function getAllByUserId(string $userId, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAllByUserId($userId, $filters, $perPage);
    }

    public function getAllByStatus(?string $status, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAllByStatus($status, $filters, $perPage);
    }
}
