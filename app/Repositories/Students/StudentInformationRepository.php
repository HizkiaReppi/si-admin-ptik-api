<?php

namespace App\Repositories\Students;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Students\StudentInformation;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentInformationRepository
{
    public function getByStudentId(string $studentrId): Collection
    {
        $query = StudentInformation::query();

        $query->where('student_id', $studentrId);

        $studentInformation = $query->get();

        return $studentInformation;
    }

    public function update(array $data, string $studentId): Student
    {
        return DB::transaction(function () use ($data, $studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                throw new ResourceNotFoundException("Student data not found");
            }

            $studentInformation = $student->information()->first();

            if (!$studentInformation) {
                $studentInformation = $student->information()->create($data);
            } else {
                $studentInformation->update($data);
            }

            $student->load('information');

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

            $studentInformation = $student->information()->first();

            if ($studentInformation) {
                $studentInformation->delete();
            }

            return true;
        });
    }
}
