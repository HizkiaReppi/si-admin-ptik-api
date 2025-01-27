<?php

namespace App\Services;

use App\Helpers\FormatterHelper;
use App\Repositories\LecturerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LecturerService
{
    private LecturerRepository $lecturerRepository;
    private FormatterHelper $formatterHelper;

    public function __construct(LecturerRepository $lecturerRepository, FormatterHelper $formatterHelper)
    {
        $this->lecturerRepository = $lecturerRepository;
        $this->formatterHelper = $formatterHelper;
    }

    /**
     * Get paginated list of lecturers with optional filters and relations.
     *
     * @param array $filters
     * @param array $relations
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getLecturers(array $filters = [], array $relations = [], ?int $perPage = 10): LengthAwarePaginator
    {
        if (!empty($filters['search'])) {
            $filters['search'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower(trim($filters['search'])));
        }

        if (!empty($filters['sortBy']) && !in_array($filters['sortBy'], ['name', 'nip', 'nidn'])) {
            $filters['sortBy'] = null;
        }

        if (!empty($filters['order']) && !in_array($filters['order'], ['asc', 'desc'])) {
            $filters['order'] = 'asc';
        }

        return $this->lecturerRepository->getAll($relations, $filters, $perPage);
    }

    /**
     * Create a new lecturer with optional related data.
     *
     * @param array $data
     * @return \App\Models\Lecturer
     */
    public function createLecturer(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_dosen_' . $data['nidn'] . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/lecturers', $filePath);
                    $data['photo'] = $filePath;
                }

                $result = $this->lecturerRepository->store($this->formatterHelper->camelToSnake($data));

                if (!$result) {
                    throw new \Exception('Gagal menyimpan foto dan data dosen');
                }

                return $result;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
