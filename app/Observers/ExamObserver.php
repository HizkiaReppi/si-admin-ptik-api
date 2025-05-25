<?php

namespace App\Observers;

use App\Models\Exam;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class ExamObserver
{
    /**
     * Handle the Submission "created" event.
     */
    public function created(Exam $exam): void
    {
        $this->clearCache($exam);
    }

    /**
     * Handle the Submission "updated" event.
     */
    public function updated(Exam $exam): void
    {
        $this->clearCache($exam);
    }

    /**
     * Handle the Submission "deleted" event.
     */
    public function deleted(Exam $exam): void
    {
        $this->clearCache($exam);
    }

    /**
     * Handle the Submission "restored" event.
     */
    public function restored(Exam $exam): void
    {
        $this->clearCache($exam);
    }

    /**
     * Handle the Submission "force deleted" event.
     */
    public function forceDeleted(Exam $exam): void
    {
        $this->clearCache($exam);
    }

    /**
     * Clear cache
     */
    private function clearCache(Exam $exam): void
    {
        $cacheKeys = Cache::get('exams_cache_keys', []);

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('exams_cache_keys');

        ResponseCache::clear();
    }
}
