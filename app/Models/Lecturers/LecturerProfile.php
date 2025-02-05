<?php

namespace App\Models\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LecturerProfile extends Model
{
    /** @use HasFactory<\Database\Factories\Lecturers\LecturerProfileFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['lecturer_id', 'platform', 'profile_url'];

    /**
     * Get the lecturer that owns the profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}

