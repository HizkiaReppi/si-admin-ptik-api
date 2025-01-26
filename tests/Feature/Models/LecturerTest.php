<?php

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a lecturer with valid data', function () {
    $lecturer = Lecturer::factory()->create([
        'front_degree' => 'Prof., Dr.',
        'back_degree' => 'S.T., M.T.',
        'position' => 'Profesor',
        'rank' => 'Pembina Utama Madya, IV/a',
        'type' => 'PNS',
    ]);

    expect($lecturer)
        ->toBeInstanceOf(Lecturer::class)
        ->and($lecturer->nip)->not()->toBeNull()
        ->and($lecturer->nidn)->not()->toBeNull()
        ->and($lecturer->user_id)->not()->toBeNull()
        ->and($lecturer->front_degree)->toBe('Prof., Dr.')
        ->and($lecturer->back_degree)->toBe('S.T., M.T.')
        ->and($lecturer->position)->toBe('Profesor')
        ->and($lecturer->rank)->toBe('Pembina Utama Madya, IV/a')
        ->and($lecturer->type)->toBe('PNS')
        ->and($lecturer->phone_number)->not()->toBeNull()
        ->and($lecturer->address)->not()->toBeNull();
});

it('ensures lecturer belongs to a user', function () {
    $user = User::factory()->create();
    $lecturer = Lecturer::factory()->create(['user_id' => $user->id]);

    expect($lecturer->user)
        ->toBeInstanceOf(User::class)
        ->and($lecturer->user->id)->toBe($user->id);
});

it('ensures nip and nidn are unique', function () {
    $lecturer1 = Lecturer::factory()->create();

    $this->expectException(\Illuminate\Database\QueryException::class);

    Lecturer::factory()->create([
        'nip' => $lecturer1->nip,
        'nidn' => $lecturer1->nidn,
    ]);
});

it('validates required fields', function () {
    $invalidData = [
        'nip' => null,
        'nidn' => null,
    ];

    expect(fn () => Lecturer::factory()->create($invalidData))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('ensure attribute length is valid', function () {
    $invalidData = [
        'nip' => '1234567890123456789',
        'nidn' => '12345678901',
    ];

    expect(fn () => Lecturer::factory()->create($invalidData))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('has expected attributes', function () {
    $lecturer = Lecturer::factory()->create();

    expect($lecturer->getAttributes())
        ->toHaveKeys([
            'id',
            'user_id',
            'nip',
            'nidn',
            'front_degree',
            'back_degree',
            'position',
            'rank',
            'type',
            'phone_number',
            'address',
            'created_at',
            'updated_at',
        ]);
});

it('can filter lecturers by type', function () {
    Lecturer::factory()->create(['type' => 'PNS']);
    Lecturer::factory()->create(['type' => 'Honorer']);

    $pnsLecturers = Lecturer::where('type', 'PNS')->get();

    expect($pnsLecturers)->toHaveCount(1)
        ->and($pnsLecturers->first()->type)->toBe('PNS');
});

it('ensures lecturer can be deleted and user is will be deleted', function () {
    $user = User::factory()->create();
    $lecturer = Lecturer::factory()->create(['user_id' => $user->id]);

    $lecturer->user()->delete();
    $lecturer->delete();

    expect(Lecturer::find($lecturer->id))->toBeNull()
        ->and(User::find($user->id))->toBeNull();
});
