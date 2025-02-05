<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'username', 'email', 'gender',
        'password', 'photo', 'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity' => 'datetime',
        ];
    }

    /**
     * Get user photo.
     *
     * @return string
     */
    public function getPhotoFileAttribute(): string
    {
        return $this->photo ? 'storage/images/profile-photo/' . $this->photo : null;
    }

    /**
     * Check if user is online.
     * 
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->last_activity && now()->diffInMinutes($this->last_activity) < 3;
    }

    /**
     * Get user last activity.
     * 
     * @return string
     */
    public function lastActivityAgo(): string
    {
        return $this->last_activity !== null ? Carbon::parse($this->last_activity)->diffForHumans() : 'Tidak Pernah Login';
    }
}
