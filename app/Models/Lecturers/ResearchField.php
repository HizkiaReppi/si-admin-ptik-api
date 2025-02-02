<?php

namespace App\Models\Lecturers;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResearchField extends Model
{
    /** @use HasFactory<\Database\Factories\Lecturers\ResearchFieldFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['lecturer_id', 'field_name', 'description'];

    /**
     * Get the lecturer that owns the research field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'lecturer_research_fields', 'research_field_id', 'lecturer_id');
    }
}
