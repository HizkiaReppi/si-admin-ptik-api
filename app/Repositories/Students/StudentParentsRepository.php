<?php

namespace App\Repositories\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Students\StudentParent;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentParentsRepository
{
    public function getByStudentId(string $studentrId): Collection
    {
        $query = StudentParent::query();

        $query->where('student_id', $studentrId);

        $studentParents = $query->get();

        return $studentParents;
    }

    public function update(array $data, string $studentId): Student
    {
        return DB::transaction(function () use ($data, $studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            $studentInformation = $student->parents()->first();

            if (!$studentInformation) {
                $studentInformation = $student->parents()->create($data);
            } else {
                $studentInformation->update($data);
            }

            $student->load('parents');

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

            $studentInformation = $student->parents()->first();

            if ($studentInformation) {
                $studentInformation->delete();
            }

            return true;
        });
    }
}
