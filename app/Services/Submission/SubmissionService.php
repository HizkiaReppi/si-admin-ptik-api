<?php

namespace App\Services\Submission;

use App\Repositories\Submission\SubmissionRepository;
use App\Models\Submission\Submission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class SubmissionService
{
    public function __construct(protected SubmissionRepository $repository) {}

    public function getAll(string $categorySlug, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAll($categorySlug, $filters, $perPage);
    }

    public function getById(string $categorySlug, string $id): ?Submission
    {
        return $this->repository->getById($categorySlug, $id);
    }

    public function verifySubmission(string $categorySlug, string $id, string $status, string $reviewerName): ?Submission
    {
        if ($status === 'faculty_review') {
            $submission = $this->repository->getById($categorySlug, $id);
            if (!$submission) {
                return null;
            }

            $documentNumberFormat = env('DOCUMENT_NUMBER_FORMAT', "");

            $currentYear = date('Y');
            $documentNumber = $this->repository->getLastDocumentNumber() . "/{$documentNumberFormat}/{$currentYear}";

            Carbon::setLocale('id');
            $documentDate = Carbon::now()->translatedFormat('d F Y');
            $filePath = "file/documents/{$submission->category->slug}/{$documentNumber}.pdf";
            Storage::disk('public')->put($filePath, 'ISI SURAT (GENERATE PDF)');

            $this->repository->updateDocumentPath($id, $documentNumber, $documentDate, $filePath);
        }

        return $this->repository->updateStatus($id, $status, $reviewerName);
    }

    public function rejectSubmission(string $id, string $reviewerName, string $reason): ?Submission
    {
        return $this->repository->updateStatus($id, 'rejected', $reviewerName, $reason);
    }
}
