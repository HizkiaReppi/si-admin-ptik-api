<?php

namespace App\Models;

use App\Models\Students\StudentAddress;
use App\Models\Students\StudentInformation;
use App\Models\Students\StudentParent;
use App\Models\Submission\Submission;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id', 'lecturer_id_1', 'lecturer_id_2', 'nim',
        'entry_year', 'class', 'gender', 'concentration', 'phone_number',
    ];

    /**
     * Get the user that owns the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the first supervisor for the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstSupervisor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_1');
    }

    /**
     * Get the second supervisor for the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function secondSupervisor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_2');
    }

    /**
     * Get the student information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function information(): HasOne
    {
        return $this->hasOne(StudentInformation::class);
    }

    /**
     * Get the student addresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(StudentAddress::class);
    }

    /**
     * Get the student parents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parents(): HasOne
    {
        return $this->hasOne(StudentParent::class);
    }

    /**
     * Get the student submissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    protected function fullname(): Attribute
    {
        return Attribute::get(fn () => $this->user->name ?? null);
    }
}
