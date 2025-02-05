<?php

namespace App\Models\Students;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAddress extends Model
{
    /** @use HasFactory<\Database\Factories\Students\StudentAddressFactory> */
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'student_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id', 'province', 'regency', 'district',
        'village', 'postal_code', 'address', 'type',
    ];

    /**
     * Get the student that owns the Student Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
