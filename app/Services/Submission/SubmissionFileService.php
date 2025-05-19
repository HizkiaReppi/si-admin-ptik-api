<?php

namespace App\Services\Submission;

use App\Repositories\Submission\SubmissionFileRepository;
use Illuminate\Support\Facades\Storage;

class SubmissionFileService
{
    public function __construct(protected SubmissionFileRepository $fileRepository) {}

    public function storeFiles(array $files, string $submissionId): void
    {
        foreach ($files as $index => $fileData) {
            $requirementId = $fileData['requirement_id'];

            $path = null;
            if (isset($fileData['file']) && $fileData['file']->isValid()) {
                $fileName = time() . '_' . $submissionId . '_requirement_' . $index + 1 . '.' . $fileData['file']->getClientOriginalExtension();
                $path = $fileData['file']->storeAs('file/submissions', $fileName, 'public');
                $path = Storage::url($path);
            } else if (isset($fileData['text'])) {
                $path = $fileData['text'];
            }

            $this->fileRepository->create([
                'submission_id' => $submissionId,
                'requirement_id' => $requirementId,
                'file_path' => $path,
            ]);
        }
    }
}
