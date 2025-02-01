<?php

use App\Models\Lecturer;
use App\Models\Lecturers\ResearchField;
use Illuminate\Database\QueryException;

it('can create a research field', function () {
    $researchField = ResearchField::factory()->create([
        'field_name' => 'Research Field A',
        'description' => 'Description of Research Field A'
    ]);

    expect($researchField)
        ->toBeInstanceOf(ResearchField::class)
        ->field_name->toBe('Research Field A')
        ->description->toBe('Description of Research Field A');
});

it('throws an error when creating research field without field_name', function () {
    ResearchField::factory()->create(['field_name' => null]);
})->throws(QueryException::class);

it('can associate a research field with a lecturer', function () {
    $lecturer = Lecturer::factory()->create();
    $researchField = ResearchField::factory()->create([
        'field_name' => 'Research Field A',
        'description' => 'Description of Research Field A'
    ]);

    $researchField->lecturers()->attach($lecturer);

    $this->assertDatabaseHas('lecturer_research_fields', [
        'lecturer_id' => $lecturer->id,
        'research_field_id' => $researchField->id
    ]);
});

it('can retrieve associated lecturers for a research field', function () {
    $lecturer = Lecturer::factory()->create();
    $researchField = ResearchField::factory()->create();

    $researchField->lecturers()->attach($lecturer);

    $lecturers = $researchField->lecturers;
    expect($lecturers->first()->id)->toBe($lecturer->id);
});


it('ensures research field can be deleted', function () {
    $researchField = ResearchField::factory()->create();

    $researchField->delete();

    expect(ResearchField::find($researchField->id))->toBeNull();
});

it('ensures associated records are deleted when a research field is deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $researchField = ResearchField::factory()->create();

    $researchField->lecturers()->attach($lecturer);

    $researchField->delete();

    $this->assertDatabaseMissing('lecturer_research_fields', [
        'lecturer_id' => $lecturer->id,
        'research_field_id' => $researchField->id
    ]);
});
