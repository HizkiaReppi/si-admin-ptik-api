<?php

use App\Models\Lecturer;
use App\Models\Lecturers\Experience;
use Illuminate\Database\QueryException;

it('can create an experience record', function () {
    $lecturer = Lecturer::factory()->create();
    $experience = Experience::factory()->create([
        'lecturer_id' => $lecturer->id,
        'position' => 'Software Engineer',
        'organization' => 'Google',
        'description' => 'Developing software',
        'start_date' => '2022-01-01',
        'end_date' => '2022-12-31',
        'is_current' => false,
    ]);

    expect($experience)
        ->toBeInstanceOf(Experience::class)
        ->lecturer->id->toBe($lecturer->id)
        ->position->toBe('Software Engineer')
        ->organization->toBe('Google')
        ->description->toBe('Developing software')
        ->start_date->toBe('2022-01-01')
        ->end_date->toBe('2022-12-31')
        ->is_current->toBeFalse();
});

it('throws an error when creating experience without lecturer_id', function () {
    Experience::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('ensures that is_current is a boolean', function () {
    Experience::factory()->create(['is_current' => 'not-a-boolean']);
})->throws(QueryException::class);

it('ensures experience can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $experience = Experience::factory()->create(['lecturer_id' => $lecturer->id]);

    $experience->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});