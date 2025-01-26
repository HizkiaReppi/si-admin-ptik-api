<?php

namespace App\Models;

use App\Models\Lecturer\CommunityService;
use App\Models\Lecturer\Education;
use App\Models\Lecturer\Experience;
use App\Models\Lecturer\Publication;
use App\Models\Lecturer\ResearchField;
use App\Models\Lecturer\ResearchProject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    /** @use HasFactory<\Database\Factories\LecturerFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id', 'nip', 'nidn', 'front_degree', 'back_degree',
        'position', 'rank', 'type', 'phone_number', 'address',
    ];

    /**
     * Get the user that owns the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the educations for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get the experiences for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get the research fields for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function researchFields(): BelongsToMany
    {
        return $this->belongsToMany(ResearchField::class, 'lecturer_research_fields', 'lecturer_id', 'research_field_id');
    }

    /**
     * Get the research projects for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchProjects(): HasMany
    {
        return $this->hasMany(ResearchProject::class);
    }

    /**
     * Get the community services for the lecturer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function communityServices(): HasMany
    {
        return $this->hasMany(CommunityService::class);
    }

    /**
     * Get the publications for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    /**
     * Get the full name of the lecturer.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        if ($this->front_degree && $this->back_degree) {
            return $this->front_degree . ' ' . $this->user->name . ', ' . $this->back_degree;
        } elseif ($this->front_degree) {
            return $this->front_degree . ' ' . $this->user->name;
        } elseif ($this->back_degree) {
            return $this->user->name . ', ' . $this->back_degree;
        } else {
            return $this->user->name;
        }
    }
}
