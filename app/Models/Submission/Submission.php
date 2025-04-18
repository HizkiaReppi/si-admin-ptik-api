<?php

namespace App\Models\Submission;

use App\Models\Student;
use App\Models\Category;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    /** @use HasFactory<\Database\Factories\Submission\SubmissionFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'category_id', 'student_id', 'status', 'reviewer_name', 'document_number',
        'document_date', 'generated_file_path', 'rejection_reason',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function examiners(): HasMany
    {
        return $this->hasMany(SubmissionExaminer::class);
    }

    public function supervisors(): HasMany
    {
        return $this->hasMany(SubmissionSupervisor::class);
    }
}
