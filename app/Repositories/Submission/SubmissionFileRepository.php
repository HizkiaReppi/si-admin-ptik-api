<?php

namespace App\Repositories\Submission;

use App\Models\Submission\SubmissionFile;

class SubmissionFileRepository
{
    public function create(array $data): SubmissionFile
    {
        return SubmissionFile::create($data);
    }
}
