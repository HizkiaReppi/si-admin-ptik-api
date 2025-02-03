<?php

namespace App\Observers;

use App\Models\Lecturer;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class LecturerObserver
{
    /**
     * Handle the Lecturer "created" event.
     */
    public function created(Lecturer $lecturer): void
    {
        $this->clearCache($lecturer);
    }

    /**
     * Handle the Lecturer "updated" event.
     */
    public function updated(Lecturer $lecturer): void
    {
        $this->clearCache($lecturer);
    }

    /**
     * Handle the Lecturer "deleted" event.
     */
    public function deleted(Lecturer $lecturer): void
    {
        $this->clearCache($lecturer);
    }

    /**
     * Handle the Lecturer "restored" event.
     */
    public function restored(Lecturer $lecturer): void
    {
        $this->clearCache($lecturer);
    }

    /**
     * Handle the Lecturer "force deleted" event.
     */
    public function forceDeleted(Lecturer $lecturer): void
    {
        $this->clearCache($lecturer);
    }

    /**
     * Clear cache
     */
    private function clearCache(Lecturer $lecturer): void
    {
        Cache::forget("lecturers_all_*");
        Cache::forget("lecturer_{$lecturer->id}");

        ResponseCache::clear();
    }
}
