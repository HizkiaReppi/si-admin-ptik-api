<?php

namespace App\Services\HeadOfDepartment;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\HeadOfDepartment;
use App\Repositories\HeadOfDepartment\HeadOfDepartmentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HeadOfDepartmentService
{
    public function __construct(
        protected HeadOfDepartmentRepository $headOfDepartmentRepository,
        protected FormatterHelper $formatterHelper
    ) { }

    public function getAll(): Collection
    {
        return $this->headOfDepartmentRepository->getAll();
    }

    public function getById(string $id): HeadOfDepartment
    {
        try {
            return $this->headOfDepartmentRepository->getById($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['signiture_file'])) {
                    $file = $data['signiture_file'];
                    $filePath = time() . '_HoD_' . $data['nim'] . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/head-of-departments', $filePath);
                    $data['signiture_file'] = $filePath;
                }

                $result = $this->headOfDepartmentRepository->store($this->formatterHelper->camelToSnake($data));

                if (!$result) {
                    throw new \Exception('Failed to create head of department');
                }

                return $result;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): HeadOfDepartment
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $headOfDepartment = $this->headOfDepartmentRepository->getById($id);

                if (isset($data['signiture_file'])) {
                    $file = $data['signiture_file'];
                    $filePath = time() . '_HoD_' . $headOfDepartment->nim . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/head-of-departments', $filePath);
                    $data['signiture_file'] = $filePath;

                    if ($headOfDepartment->signiture_file !== null) {
                        $oldImagePath = 'public/images/head-of-departments/' . $headOfDepartment->signiture_file;
                        if (Storage::exists($oldImagePath)) {
                            Storage::delete($oldImagePath);
                        }
                    }
                }

                $result = $this->headOfDepartmentRepository->update($this->formatterHelper->camelToSnake($data), $id);

                if (!$result) {
                    throw new \Exception('Failed to update head of department');
                }

                return $result;
            });
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $result = $this->headOfDepartmentRepository->delete($id);

                if (!$result) {
                    throw new \Exception('Failed to delete head of department');
                }

                if ($result['signitureFile'] !== null) {
                    $oldImagePath = 'public/images/head-of-departments/' . $result['signitureFile'];
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
