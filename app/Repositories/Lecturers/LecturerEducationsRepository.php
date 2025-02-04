<?php

namespace App\Repositories\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturers\Education;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LecturerEducationsRepository
{
    public function getByLecturerId(string $lecturerId): Collection
    {
        $query = Education::query();

        $query->where('lecturer_id', $lecturerId);

        $educations = $query->get();

        return $educations;
    }

    public function update(array $data, string $lecturerId)
    {
        return DB::transaction(function () use ($data, $lecturerId) {
            $lecturer = Lecturer::find($lecturerId);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $existingEducations = $lecturer->educations()->get()->keyBy(function ($education) {
                return $education->degree . '|' . $education->field_of_study . '|' . $education->institution;
            });

            $incomingEducations = collect($data)->keyBy(function ($education) {
                return $education['degree'] . '|' . $education['field_of_study'] . '|' . $education['institution'];
            });

            $toCreate = $incomingEducations->diffKeys($existingEducations);
            $toUpdate = $incomingEducations->intersectByKeys($existingEducations);
            $toDelete = $existingEducations->diffKeys($incomingEducations);

            foreach ($toCreate as $education) {
                $lecturer->educations()->create($education);
            }

            foreach ($toUpdate as $key => $education) {
                $existingEducations[$key]->update($education);
            }

            foreach ($toDelete as $education) {
                $education->delete();
            }

            $lecturer->load('educations');

            return $lecturer;
        });
    }

    public function delete(string $lecturerId, string $educationId)
    {
        $lecturer = Lecturer::find($lecturerId);

        if (!$lecturer) {
            throw new ResourceNotFoundException("Lecturer data not found");
        }

        $education = $lecturer->educations()->find($educationId);

        if (!$education) {
            throw new ResourceNotFoundException("Education data not found");
        }

        $education->delete();

        return true;
    }
}
