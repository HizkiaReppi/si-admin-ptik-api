<?php

namespace App\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturers\LecturerProfile;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LecturerProfileRepository
{
    public function getByLecturerId(string $lecturerId): Collection
    {
        $query = LecturerProfile::query();

        $query->where('lecturer_id', $lecturerId);

        $lecturerProfiles = $query->get();

        return $lecturerProfiles;
    }

    public function update(array $data, string $lecturerId)
    {
        return DB::transaction(function () use ($data, $lecturerId) {
            $lecturer = Lecturer::find($lecturerId);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $existingProfiles = $lecturer->profiles()->get()->keyBy(function ($profiles) {
                return $profiles->platform . '|' . $profiles->profile_url;
            });

            $incomingProfiles = collect($data)->keyBy(function ($profiles) {
                return $profiles['platform'] . '|' . $profiles['profile_url'];
            });

            $toCreate = $incomingProfiles->diffKeys($existingProfiles);
            $toUpdate = $incomingProfiles->intersectByKeys($existingProfiles);
            $toDelete = $existingProfiles->diffKeys($incomingProfiles);

            foreach ($toCreate as $profiles) {
                $lecturer->profiles()->create($profiles);
            }

            foreach ($toUpdate as $key => $profiles) {
                $existingProfiles[$key]->update($profiles);
            }

            foreach ($toDelete as $profiles) {
                $profiles->delete();
            }

            $lecturer->load('profiles');

            return $lecturer;
        });
    }

    public function delete(string $lecturerId, string $profileId): bool
    {
        $lecturer = Lecturer::find($lecturerId);

        if (!$lecturer) {
            throw new ResourceNotFoundException("Lecturer data not found");
        }

        $profile = $lecturer->profiles()->find($profileId);

        if (!$profile) {
            throw new ResourceNotFoundException("External Profiles data not found");
        }

        $profile->delete();

        return true;
    }
}
