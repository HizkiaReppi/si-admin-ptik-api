<?php

use App\Models\Lecturer;
use App\Models\Lecturers\LecturerProfile;
use Illuminate\Database\QueryException;

it('can create an external lecturer profile record', function () {
    $lecturer = Lecturer::factory()->create();
    $experience = LecturerProfile::factory()->create([
        'lecturer_id' => $lecturer->id,
        'platform' => 'pddikti',
        'profile_url' => 'https://pddikti.kemdiktisaintek.go.id/',
    ]);

    expect($experience)
        ->toBeInstanceOf(LecturerProfile::class)
        ->lecturer->id->toBe($lecturer->id)
        ->platform->toBe('pddikti')
        ->profile_url->toBe('https://pddikti.kemdiktisaintek.go.id/');
});

it('throws an error when creating external lecturer profile without lecturer_id', function () {
    LecturerProfile::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('ensures that platform is a platform valid', function () {
    LecturerProfile::factory()->create(['platform' => 'not-a-platform-valid']);
})->throws(QueryException::class);

it('ensures external lecturer profile can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $experience = LecturerProfile::factory()->create(['lecturer_id' => $lecturer->id]);

    $experience->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});
