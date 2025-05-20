<?php

namespace App\Models;

use App\Models\Submission\Submission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'submission_id',
        'exam_date',
        'exam_time',
        'exam_place',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function getTypeAttribute($value)
    {
        return match ($value) {
            'seminar-proposal' => 'Seminar Proposal',
            'ujian-hasil-penelitian' => 'Ujian Hasil Penelitian',
            'ujian-komprehensif' => 'Ujian Komprehensif',
            default => $value,
        };
    }

    public function getExamDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->translatedFormat('l, d F Y');
    }

    public function getExamTimeAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->translatedFormat('H:i');
    }

    public function getExamPlaceAttribute($value)
    {
        return $value;
    }
}
