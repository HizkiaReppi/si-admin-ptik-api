<?php

namespace App\Services\Admin;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\FormatterHelper;
use App\Models\User;
use App\Repositories\Admin\AdminRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminService
{
    public function __construct(
        protected AdminRepository $adminRepository,
        protected FormatterHelper $formatterHelper
    ) { }

    public function getAll(): Collection
    {
        return $this->adminRepository->getAll();
    }

    public function getById(string $id): User
    {
        try {
            return $this->adminRepository->getById($id);
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(array $data): User
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_admin_' . $data['username'] . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/administrators', $filePath);
                    $data['photo'] = $filePath;
                }

                $result = $this->adminRepository->store($this->formatterHelper->camelToSnake($data));

                if (!$result) {
                    throw new \Exception('Failed to create administrator');
                }

                return $result;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): User
    {
        try {
            return DB::transaction(function () use ($data, $id) {
                $admin = $this->adminRepository->getById($id);

                if (isset($data['photo'])) {
                    $file = $data['photo'];
                    $filePath = time() . '_admin_' . $admin->username . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/images/administrators', $filePath);
                    $data['photo'] = $filePath;

                    if ($admin->photo !== null) {
                        $oldImagePath = 'public/images/administrators/' . $admin->photo;
                        if (Storage::exists($oldImagePath)) {
                            Storage::delete($oldImagePath);
                        }
                    }
                }

                $result = $this->adminRepository->update($this->formatterHelper->camelToSnake($data), $id);

                if (!$result) {
                    throw new \Exception('Failed to update administrator');
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
                $result = $this->adminRepository->delete($id);

                if (!$result) {
                    throw new \Exception('Failed to delete administrator');
                }

                if ($result->photo !== null) {
                    $oldImagePath = 'public/images/administrators/' . $result->photo;
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
