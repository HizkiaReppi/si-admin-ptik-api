<?php

use App\Models\Lecturer;
use App\Models\Lecturers\Education;
use Illuminate\Database\QueryException;

it('can create an education record', function () {
    $lecturer = Lecturer::factory()->create();
    $education = Education::factory()->create([
        'lecturer_id' => $lecturer->id,
        'degree' => 'S1',
        'field_of_study' => 'Teknik Informatika',
        'institution' => 'Universitas Negeri Manado',
        'graduation_year' => 2010,
        'thesis_title' => 'Pengembangan Sistem Informasi',
    ]);

    expect($education)
        ->toBeInstanceOf(Education::class)
        ->lecturer->id->toBe($lecturer->id)
        ->degree->toBe('S1')
        ->field_of_study->toBe('Teknik Informatika')
        ->institution->toBe('Universitas Negeri Manado')
        ->graduation_year->toBe(2010)
        ->thesis_title->toBe('Pengembangan Sistem Informasi');
});

it('throws an error when creating education without lecturer_id', function () {
    Education::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('ensures that graduation_year is a number', function () {
    Education::factory()->create(['graduation_year' => 'not-a-number']);
})->throws(QueryException::class);

it('ensures education can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $education = Education::factory()->create(['lecturer_id' => $lecturer->id]);

    $education->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});
