<?php

namespace App\Observers;

use App\Models\Lecturers\ResearchField;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class ResearchFieldObserver
{
    /**
     * Handle the ResearchField "created" event.
     */
    public function created(ResearchField $researchField): void
    {
        $this->clearCache($researchField);
    }

    /**
     * Handle the ResearchField "updated" event.
     */
    public function updated(ResearchField $researchField): void
    {
        $this->clearCache($researchField);
    }

    /**
     * Handle the ResearchField "deleted" event.
     */
    public function deleted(ResearchField $researchField): void
    {
        $this->clearCache($researchField);
    }

    /**
     * Handle the ResearchField "restored" event.
     */
    public function restored(ResearchField $researchField): void
    {
        $this->clearCache($researchField);
    }

    /**
     * Handle the ResearchField "force deleted" event.
     */
    public function forceDeleted(ResearchField $researchField): void
    {
        $this->clearCache($researchField);
    }

    /**
     * Clear cache
     */
    private function clearCache(ResearchField $researchField): void
    {
        Cache::forget("researchFields_all_*");
        Cache::forget("researchField_{$researchField->id}");

        ResponseCache::clear();
    }
}
