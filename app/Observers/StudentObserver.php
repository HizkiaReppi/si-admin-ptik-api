<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\Submission\Submission;
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
        Cache::forget('students_count');
        Cache::forget("student_{$student->id}");
        
        $subCacheKeys = Cache::get('submissions_cache_keys', []);

        foreach ($subCacheKeys as $key) {
            Cache::forget($key);
        }

        $submission = $student->submissions;

        if ($submission->isEmpty()) {
            return;
        }

        foreach ($submission as $sub) {
            Cache::forget("submission_{$sub->category->slug}_{$sub->id}");
        }

        Cache::forget('submissions_cache_keys');

        ResponseCache::clear();
    }
}
