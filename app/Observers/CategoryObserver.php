<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->clearCache($category);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->clearCache($category);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->clearCache($category);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clearCache($category);
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $this->clearCache($category);
    }

    /**
     * Clear cache
     */
    private function clearCache(Category $category): void
    {
        $cacheKeys = Cache::get('categories_cache_keys', []);

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('categories_cache_keys');
        Cache::forget("category_{$category->id}");

        ResponseCache::clear();
    }
}
