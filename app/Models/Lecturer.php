<?php

namespace App\Models;

use App\Models\Lecturers\Education;
use App\Models\Lecturers\Experience;
use App\Models\Lecturers\LecturerProfile;
use App\Models\Lecturers\ResearchField;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        'user_id',
        'nip',
        'nidn',
        'front_degree',
        'back_degree',
        'position',
        'rank',
        'type',
        'phone_number',
        'address',
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
     * Get the students where the lecturer is the first supervisor.
     */
    public function firstSupervisedStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'lecturer_id_1');
    }

    /**
     * Get the students where the lecturer is the second supervisor.
     */
    public function secondSupervisedStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'lecturer_id_2');
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
     * Get the profiles for the lecturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(LecturerProfile::class);
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

    /**
     * Get the students supervised by the lecturer.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSupervisedStudentsAttribute(): Collection
    {
        return $this->firstSupervisedStudents->merge($this->secondSupervisedStudents);
    }
}
