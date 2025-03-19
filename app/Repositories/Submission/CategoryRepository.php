<?php

namespace App\Repositories\Submission;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all lecturers with optional relations, search, and pagination.
     *
     * @param array $relations
     * @param array $search
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = "categories_all_{$perPage}_page_" . request()->get('page', 1) . "_" . md5(json_encode($filters));

        $cacheKeys = Cache::get('categories_cache_keys', []);
        $cacheKeys[] = $cacheKey;
        Cache::put('categories_cache_keys', array_unique($cacheKeys), 604800);

        return Cache::remember($cacheKey, 604800, function () use ($filters, $perPage) {
            $query = Category::query();

            $query->with(['requirements']);

            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];

                $query->where('name', 'like', "%{$searchTerm}%");
            }

            if (!empty($filters['sortBy']) && !empty($filters['order'])) {
                $sortBy = $filters['sortBy'];
                $sortOrder = $filters['order'];

                if ($sortBy === 'name') {
                    $query->orderBy('name', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            }

            return $query->paginate($perPage);
        });
    }


    public function getById(string $id): Category
    {
        $cacheKey = "category_{$id}";

        $cacheKeys = Cache::get('categories_cache_keys', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('categories_cache_keys', $cacheKeys, 604800);
        }

        return Cache::remember($cacheKey, 604800, function () use ($id) {
            $query = Category::query();

            $query->with(['requirements']);

            $researchField = $query->find($id);

            if (!$researchField) {
                throw new ResourceNotFoundException("Category Field data not found");
            }

            return $researchField;
        });
    }

    public function store(array $data): Category
    {
        try {
            $categoryExist = Category::where('name', $data['name'])->first();

            if ($categoryExist) {
                throw new \Exception("Category already exists", 409);
            }

            $slug = strtolower(str_replace(' ', '-', $data['name']));

            return DB::transaction(function () use ($data, $slug) {
                $category = Category::create([
                    'name' => $data['name'],
                    'slug' => $slug,
                    'docs_file_path' => $data['docs_file_path'],
                ]);

                if (isset($data['requirements'])) {
                    foreach ($data['requirements'] as $index => $requirement) {
                        $filePath = null;
                        if (isset($requirement['file'])) {
                            $file = $requirement['file'];
                            $fileName = time() . '_' . $category->slug . '_persyaratan_' . $index + 1 . '.' . $file->getClientOriginalExtension();
                            $filePath = $file->storeAs('public/file/requirements', $fileName);
                        }
                        $category->requirements()->create([
                            'name' => $requirement['name'],
                            'type' => $requirement['type'],
                            'file_path' => $filePath ? Storage::url($filePath) : null,
                        ]);
                    }
                }

                $category->load('requirements');

                return $category;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function update(array $data, string $id): Category
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $category = Category::find($id);

                if (!$category) {
                    throw new ResourceNotFoundException("Category data not found");
                }

                if (isset($data['name'])) {
                    $categoryExist = Category::where('name', $data['name'])->where('id', '!=', $id)->first();

                    if ($categoryExist) {
                        throw new \Exception("Category already exists", 409);
                    }

                    $slug = strtolower(str_replace(' ', '-', $data['name']));

                    $data['slug'] = $slug;

                    $category->update($data);

                    foreach ($category->requirements as $requirement) {
                        if ($requirement->file_path) {
                            Storage::delete($requirement->file_path);
                        }
                        $requirement->delete();
                    }

                    if (isset($data['requirements'])) {
                        foreach ($data['requirements'] as $index => $requirement) {
                            $filePath = null;
                            if (isset($requirement['file'])) {
                                $fileName = time() . '_' . str_replace(' ', '_', $category->name) . '_persyaratan_' . $index . '.' . $requirement['file']->getClientOriginalExtension();
                                $filePath = $requirement['file']->storeAs('public/file/requirements', $fileName);
                            }
                            $category->requirements()->create([
                                'name' => $requirement['name'],
                                'type' => $requirement['type'],
                                'file_path' => $filePath,
                            ]);
                        }
                    }
                }
                return $category;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function delete(string $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $category = Category::find($id);

                if (!$category) {
                    throw new ResourceNotFoundException("Category data not found");
                }

                foreach ($category->requirements as $requirement) {
                    if ($requirement->file_path) {
                        Storage::delete($requirement->file_path);
                    }
                    $requirement->delete();
                }

                $category->delete();

                return true;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
