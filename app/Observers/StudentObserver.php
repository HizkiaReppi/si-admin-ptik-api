<?php

namespace App\Observers;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $this->clearCache($student);
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $this->clearCache($student);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        $this->clearCache($student);
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        $this->clearCache($student);
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        $this->clearCache($student);
    }

    /**
     * Clear cache
     */
    private function clearCache(Student $student): void
    {
        $cacheKeys = Cache::get('students_cache_keys', []);

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('students_cache_keys');
        Cache::forget("student_{$student->id}");

        ResponseCache::clear();
    }
}
