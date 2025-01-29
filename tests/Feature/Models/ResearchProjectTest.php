<?php

use App\Models\Lecturer;
use App\Models\Lecturers\ResearchProject;
use Illuminate\Database\QueryException;

it('can create a research project', function () {
    $lecturer = Lecturer::factory()->create();

    $researchProject = ResearchProject::factory()->create([
        'lecturer_id' => $lecturer->id,
        'title' => 'Penelitian A',
        'description' => 'Deskripsi Penelitian A',
        'year' => 2022,
        'funding_source' => 'Sumber Dana A',
        'budget' => 1000000.00,
    ]);

    expect($researchProject)
        ->toBeInstanceOf(ResearchProject::class)
        ->lecturer->id->toBe($lecturer->id)
        ->title->toBe('Penelitian A')
        ->description->toBe('Deskripsi Penelitian A')
        ->year->toBe(2022)
        ->funding_source->toBe('Sumber Dana A')
        ->budget->toBe(1000000.00);
});

it('throws an error when creating a research project without lecturer_id', function () {
    ResearchProject::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('ensures that the budget is a number', function () {
    ResearchProject::factory()->create(['budget' => 'not-a-number']);
})->throws(QueryException::class);

it('ensures research project can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $researchProject = ResearchProject::factory()->create(['lecturer_id' => $lecturer->id]);

    $researchProject->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});
