<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class DocumentObserver
{
    public function created(Document $document): void
    {
        $this->clearCache($document);
    }

    public function updated(Document $document): void
    {
        $this->clearCache($document);
    }

    public function deleted(Document $document): void
    {
        $this->clearCache($document);
    }

    public function forceDeleted(Document $document): void
    {
        $this->clearCache($document);
    }

    private function clearCache(Document $document): void
    {
        $documentable = $document->documentable;

        if ($documentable instanceof \App\Models\Submission\Submission) {
            $cacheKeys = Cache::get('submissions_cache_keys', []);

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            Cache::forget('submissions_cache_keys');
            Cache::forget('all_submissions_count');
            Cache::forget("submission_{$documentable->category->slug}_{$documentable->id}");
        }

        ResponseCache::clear();
    }
}
