<?php

namespace App\Services\Exams;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Exam;
use App\Repositories\Exams\ExamsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExamsService
{
    public function __construct(protected ExamsRepository $repository) {}

    /**
     * Get paginated list of lecturers with optional filters and relations.
     *
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(string $slug, array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        if (!empty($filters['search'])) {
            $filters['search'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower(trim($filters['search'])));
        }

        if (!empty($filters['sortBy']) && !in_array($filters['sortBy'], ['name', 'nip', 'nidn'])) {
            $filters['sortBy'] = null;
        }

        if (!empty($filters['order']) && !in_array($filters['order'], ['asc', 'desc'])) {
            $filters['order'] = 'asc';
        }

        return $this->repository->getAll($slug, $filters, $perPage);
    }

    public function create(string $submissionId): Exam
    {
        try {
            return $this->repository->create($submissionId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $submissionId, array $data): ?Exam
    {
        try {
            $exam = $this->repository->getById($submissionId);

            if (!$exam) {
                throw new Exception('Ujian tidak ditemukan.');
            }

            $document = $exam->document;
            if ($document) {
                if ($document->document_number !== null) {
                    $documentNumber = $document->document_number;
                }

                if ($document->document_date !== null) {
                    $documentDate = $document->document_date;
                }
            } else {
                $documentNumber = null;
                $documentDate = null;
            }

            if ($documentNumber === null) {
                $documentNumberFormat = env('DOCUMENT_NUMBER_FORMAT', '');

                $currentYear = date('Y');
                $documentNumber = $this->repository->getLastDocumentNumber() . "/{$documentNumberFormat}/{$currentYear}";
            }
            if ($documentDate === null) {
                Carbon::setLocale('id');
                $documentDate = Carbon::now()->translatedFormat('d F Y');
            }

            return $this->repository->update($submissionId, $data, $documentNumber, $documentDate);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $submissionId): ?Exam
    {
        try {
            return $this->repository->delete($submissionId);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
