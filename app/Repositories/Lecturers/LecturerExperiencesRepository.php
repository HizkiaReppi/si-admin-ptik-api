<?php

namespace App\Repositories\Lecturers;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturer;
use App\Models\Lecturers\Experience;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LecturerExperiencesRepository
{
    public function getByLecturerId(string $lecturerId): Collection
    {
        $query = Experience::query();

        $query->where('lecturer_id', $lecturerId);

        $experiences = $query->get();

        return $experiences;
    }

    public function update(array $data, string $lecturerId)
    {
        return DB::transaction(function () use ($data, $lecturerId) {
            $lecturer = Lecturer::find($lecturerId);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $existingExperiences = $lecturer->experiences()->get()->keyBy(function ($experience) {
                return $experience->position . '|' . $experience->organization;
            });

            $incomingExperiences = collect($data)->keyBy(function ($experience) {
                return $experience['position'] . '|' . $experience['organization'];
            });

            $toCreate = $incomingExperiences->diffKeys($existingExperiences);
            $toUpdate = $incomingExperiences->intersectByKeys($existingExperiences);
            $toDelete = $existingExperiences->diffKeys($incomingExperiences);

            foreach ($toCreate as $experience) {
                $lecturer->experiences()->create($experience);
            }

            foreach ($toUpdate as $key => $experience) {
                $existingExperiences[$key]->update($experience);
            }

            foreach ($toDelete as $experience) {
                $experience->delete();
            }

            $lecturer->load('experiences');

            return $lecturer;
        });
    }

    public function delete(string $lecturerId, string $experienceId)
    {
        $lecturer = Lecturer::find($lecturerId);

        if (!$lecturer) {
            throw new ResourceNotFoundException("Lecturer data not found");
        }

        $experience = $lecturer->experiences()->find($experienceId);

        if (!$experience) {
            throw new ResourceNotFoundException("Experience data not found");
        }

        $experience->delete();

        return true;
    }
}
