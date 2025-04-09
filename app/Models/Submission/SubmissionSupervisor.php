<?php

namespace App\Models\Submission;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionSupervisor extends Model
{
    /** @use HasFactory<\Database\Factories\Submission\SubmissionSupervisorFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['submission_id', 'examiner_id'];

    /**
     * Get the submission that the examiner is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the examiner that is assigned to the submission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examiner(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
