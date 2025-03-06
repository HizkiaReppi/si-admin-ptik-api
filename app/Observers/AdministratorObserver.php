<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class AdministratorObserver
{
    /**
     * Handle the Administrator "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role === 'admin') {
            $this->clearCache($user);
        }
    }

    /**
     * Handle the Administrator "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->role === 'admin') {
            $this->clearCache($user);
        }
    }

    /**
     * Handle the Administrator "deleted" event.
     */
    public function deleted(User $user): void
    {
        if ($user->role === 'admin') {
            $this->clearCache($user);
        }
    }

    /**
     * Handle the Administrator "restored" event.
     */
    public function restored(User $user): void
    {
        if ($user->role === 'admin') {
            $this->clearCache($user);
        }
    }

    /**
     * Handle the Administrator "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        if ($user->role === 'admin') {
            $this->clearCache($user);
        }
    }

    /**
     * Clear cache
     */
    private function clearCache(User $user): void
    {
        Cache::forget('administrators');
        Cache::forget("administrator_{$user->id}");

        ResponseCache::clear();
    }
}
