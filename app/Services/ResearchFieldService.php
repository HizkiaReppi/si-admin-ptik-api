<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturers\ResearchField;
use App\Repositories\ResearchFieldRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResearchFieldService
{
    private ResearchFieldRepository $researchFieldRepository;

    public function __construct(ResearchFieldRepository $researchFieldRepository)
    {
        $this->researchFieldRepository = $researchFieldRepository;
    }

    /**
     * Get paginated list of research field with optional filters.
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

        return $this->researchFieldRepository->getAll($filters, $perPage);
    }

    public function getById(string $id): ResearchField
    {
        try {
            return $this->researchFieldRepository->getById($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        }
    }

    public function create(array $data): ResearchField
    {
        try {
            return $this->researchFieldRepository->store($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): ResearchField
    {
        try {
            return $this->researchFieldRepository->update($data, $id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            return $this->researchFieldRepository->delete($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
