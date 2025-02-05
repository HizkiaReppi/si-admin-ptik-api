<?php

namespace App\Services\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\Lecturer;
use App\Repositories\Lecturers\LecturerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
     * Get lecturer by ID with optional relations.
     *
     * @param string $id
     * @param array $relations
     * @return \App\Models\Lecturer
     */
    public function getLecturerById(string $id, array $relations = []): Lecturer
    {
        try {
            return $this->lecturerRepository->getById($id, $relations);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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

    /**
     * Update a lecturer by ID with optional related data.
     *
     * @param string $id
     * @param array $data
     * @return \App\Models\Lecturer
     */
    public function updateLecturer(array $data, string $id)
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $lecturer = $this->lecturerRepository->getById($id);

                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_dosen_' . $lecturer->nidn . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/lecturers', $filePath);
                    $data['photo'] = $filePath;

                    if ($lecturer->photo !== null) {
                        $oldImagePath = 'public/images/lecturers/' . $lecturer->photo;
                        if (Storage::exists($oldImagePath)) {
                            Storage::delete($oldImagePath);
                        }
                    }
                }

                $result = $this->lecturerRepository->update($this->formatterHelper->camelToSnake($data), $id);

                if (!$result) {
                    throw new \Exception('Gagal menyimpan foto dan data dosen');
                }

                return $result;
            });
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Delete a lecturer by ID.
     *
     * @param string $id
     */
    public function deleteLecturer(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $result = $this->lecturerRepository->delete($id);

                if (!$result) {
                    throw new \Exception('Gagal menghapus data dosen');
                }

                if ($result['photo'] !== null) {
                    $oldImagePath = 'public/images/lecturers/' . $result['photo'];
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                return $result;
            });
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
