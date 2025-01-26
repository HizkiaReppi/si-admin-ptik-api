<?php

namespace App\Models\Lecturer;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lecturer_id', 'position', 'organization', 'description',
        'start_date', 'end_date',
    ];

    /**
     * Get the lecturer that owns the experience.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
