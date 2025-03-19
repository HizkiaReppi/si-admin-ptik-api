<?php

namespace App\Observers;

use App\Models\HeadOfDepartment;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class HeadOfDepartmentObserver
{
    /**
     * Handle the Head Of Department "created" event.
     */
    public function created(HeadOfDepartment $headOfDepartment): void
    {
        $this->clearCache($headOfDepartment);
    }

    /**
     * Handle the Head Of Department "updated" event.
     */
    public function updated(HeadOfDepartment $headOfDepartment): void
    {
        $this->clearCache($headOfDepartment);
    }

    /**
     * Handle the Head Of Department "deleted" event.
     */
    public function deleted(HeadOfDepartment $headOfDepartment): void
    {
        $this->clearCache($headOfDepartment);
    }

    /**
     * Handle the Head Of Department "restored" event.
     */
    public function restored(HeadOfDepartment $headOfDepartment): void
    {
        $this->clearCache($headOfDepartment);
    }

    /**
     * Handle the Head Of Department "force deleted" event.
     */
    public function forceDeleted(HeadOfDepartment $headOfDepartment): void
    {
        $this->clearCache($headOfDepartment);
    }

    /**
     * Clear cache
     */
    private function clearCache(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('head_of_departments');
        Cache::forget("head_of_department_{$headOfDepartment->id}");

        ResponseCache::clear();
    }
}
