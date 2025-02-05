<?php

namespace App\Models\Students;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParent extends Model
{
    /** @use HasFactory<\Database\Factories\Students\StudentParentFactory> */
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'student_parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id', 'father_name', 'mother_name', 'parent_phone_number',
        'father_occupation', 'mother_occupation', 'income',
    ];

    /**
     * Get the student that owns the Student Parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
