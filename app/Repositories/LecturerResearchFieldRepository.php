<?php

namespace App\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Lecturer;
use App\Models\Lecturers\ResearchField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LecturerResearchFieldRepository
{
    public function getByLecturerId(string $lecturerId): Collection
    {
        $lecturer = Lecturer::find($lecturerId);

        if (!$lecturer) {
            throw new ResourceNotFoundException("Lecturer data not found");
        }

        $researchFields = $lecturer->researchFields()->get();

        return $researchFields;
    }

    public function update(array $data, string $lecturerId)
    {
        return DB::transaction(function () use ($data, $lecturerId) {
            $lecturer = Lecturer::find($lecturerId);

            if (!$lecturer) {
                throw new ResourceNotFoundException("Lecturer data not found");
            }

            $fieldIds = collect($data)->pluck('field_name')->toArray();

            $validResearchFields = ResearchField::whereIn('id', $fieldIds)->pluck('id')->toArray();
            $invalidFields = array_diff($fieldIds, $validResearchFields);

            if (!empty($invalidFields)) {
                throw new ResourceNotFoundException("Some research fields are invalid: " . implode(', ', $invalidFields));
            }

            $lecturer->researchFields()->sync($fieldIds);

            $lecturer->load('researchFields');

            return $lecturer;
        });
    }

    public function delete(string $lecturerId, string $researchFieldId)
    {
        $lecturer = Lecturer::find($lecturerId);

        if (!$lecturer) {
            throw new ResourceNotFoundException("Lecturer data not found");
        }

        $exists = $lecturer->researchFields()->where('research_field_id', $researchFieldId)->exists();

        if (!$exists) {
            throw new ResourceNotFoundException("Research Field data not found for this lecturer");
        }

        $lecturer->researchFields()->detach($researchFieldId);

        return true;
    }
}
