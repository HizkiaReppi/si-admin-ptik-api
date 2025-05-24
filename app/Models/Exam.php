<?php

namespace App\Models;

use App\Models\Submission\Submission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Exam extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'submission_id',
        'exam_date',
        'exam_time',
        'exam_place',
        'submission_result_doc_num',
    ];

    public function document(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function getTypeAttribute($value): string
    {
        return match ($value) {
            'seminar-proposal' => 'Seminar Proposal',
            'ujian-hasil-penelitian' => 'Ujian Hasil Penelitian',
            'ujian-komprehensif' => 'Ujian Komprehensif',
            default => $value,
        };
    }

    public function getExamDateAttribute($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        return \Carbon\Carbon::parse($value)->translatedFormat('l, d F Y');
    }

    public function getExamTimeAttribute($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        return \Carbon\Carbon::parse($value)->translatedFormat('H:i');
    }

    public function getExamPlaceAttribute($value): ?string
    {
        return $value;
    }
}
