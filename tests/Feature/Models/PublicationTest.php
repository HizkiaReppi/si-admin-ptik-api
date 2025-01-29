<?php

use App\Models\Lecturer;
use App\Models\Lecturers\Publication;
use Illuminate\Database\QueryException;

it('can create a publication', function () {
    $lecturer = Lecturer::factory()->create();
    $publication = Publication::factory()->create([
        'lecturer_id' => $lecturer->id,
        'title' => 'Publication A',
        'description' => 'Description of Publication A',
        'publication_type' => 'article',
        'publisher' => 'Publisher A',
        'publication_date' => '2022-01-01',
        'doi' => '1234567890',
    ]);

    expect($publication)
        ->toBeInstanceOf(Publication::class)
        ->lecturer->id->toBe($lecturer->id)
        ->title->toBe('Publication A')
        ->description->toBe('Description of Publication A') 
        ->publication_type->toBe('article')
        ->publisher->toBe('Publisher A')
        ->publication_date->toBe('2022-01-01')
        ->doi->toBe('1234567890')
        ->issn->toBeNull()
        ->isbn->toBeNull()
        ->author->toBeNull();
});

it('throws an error when creating publication without lecturer_id', function () {
    Publication::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('throws an error when creating publication with invalid publication_type', function () {
    Publication::factory()->create(['publication_type' => 'invalid']);
})->throws(QueryException::class);

it('ensures publication can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $publication = Publication::factory()->create(['lecturer_id' => $lecturer->id]);

    $publication->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});