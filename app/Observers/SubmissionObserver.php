<?php

namespace App\Observers;

use App\Models\Submission\Submission;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class SubmissionObserver
{
    /**
     * Handle the Submission "created" event.
     */
    public function created(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "updated" event.
     */
    public function updated(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "deleted" event.
     */
    public function deleted(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "restored" event.
     */
    public function restored(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "force deleted" event.
     */
    public function forceDeleted(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Clear cache
     */
    private function clearCache(Submission $submission): void
    {
        $cacheKeys = Cache::get('submissions_cache_keys', []);

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('submissions_cache_keys');
        Cache::forget('all_submissions_count');
        Cache::forget("submission_{$submission->category->slug}_{$submission->id}");

        if ($submission->status === 'completed') {
            $examsCacheKeys = Cache::get('exams_cache_keys', []);

            foreach ($examsCacheKeys as $key) {
                Cache::forget($key);
            }
    
            Cache::forget('exams_cache_keys');
        }

        ResponseCache::clear();
    }
}
