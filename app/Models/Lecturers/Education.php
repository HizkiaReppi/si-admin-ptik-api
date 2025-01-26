<?php

namespace App\Models\Lecturer;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'educations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lecturer_id', 'degree', 'field_of_study', 'institution',
        'graduation_year', 'thesis_title',
    ];

    /**
     * Get the lecturer that owns the education.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
