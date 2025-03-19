<?php

namespace App\Repositories\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Student;
use App\Models\Students\StudentAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StudentAddressRepository
{
    public function getByStudentId(string $studentrId): Collection
    {
        $query = StudentAddress::query();

        $query->where('student_id', $studentrId);

        $studentAddress = $query->get();

        return $studentAddress;
    }

    public function update(array $data, string $studentId): Student
    {
        return DB::transaction(function () use ($data, $studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            $existingStudentAddresses = $student->addresses()->get()->keyBy('id');

            foreach ($data as $address) {
                if (!is_array($address)) {
                    throw new \InvalidArgumentException("Each address must be an array.");
                }

                if (isset($address['id']) && $existingStudentAddresses->has($address['id'])) {
                    $student->addresses()->where('id', $address['id'])->update(Arr::only($formattedData, ['province', 'regency', 'district', 'village', 'postal_code', 'address']));
                } else {
                    $student->addresses()->create(Arr::only($address, ['address', 'province', 'regency', 'district', 'village', 'postal_code']));
                }
            }

            $student->load('addresses');

            return $student;
        });
    }

    public function delete(string $studentId): bool
    {
        return DB::transaction(function () use ($studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            $student->addresses()->delete();

            return true;
        });
    }
}
