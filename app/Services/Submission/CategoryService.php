<?php

namespace App\Services\Submission;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Category;
use App\Repositories\Submission\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) { }

    /**
     * Get paginated list of categories with optional filters.
     *
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], ?int $perPage = 10): LengthAwarePaginator
    {
        if (!empty($filters['search'])) {
            $filters['search'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower(trim($filters['search'])));
        }

        return $this->categoryRepository->getAll($filters, $perPage);
    }

    public function getById(string $id): Category
    {
        try {
            return $this->categoryRepository->getById($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        }
    }

    public function create(array $data): Category
    {
        try {
            return $this->categoryRepository->store($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): Category
    {
        try {
            return $this->categoryRepository->update($data, $id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            return $this->categoryRepository->delete($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
