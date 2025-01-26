<?php

use App\Models\Lecturer;
use App\Models\Lecturers\CommunityService;
use Illuminate\Database\QueryException;

it('can create a community service', function () {
    $lecturer = Lecturer::factory()->create();

    $communityService = CommunityService::factory()->create([
        'lecturer_id' => $lecturer->id,
        'title' => 'Pengabdian Masyarakat A',
        'description' => 'Deskripsi Pengabdian Masyarakat A',
        'year' => 2022,
        'funding_source' => 'Sumber Dana A',
        'budget' => 1000000.00,
    ]);

    expect($communityService)
        ->toBeInstanceOf(CommunityService::class)
        ->lecturer->id->toBe($lecturer->id)
        ->title->toBe('Pengabdian Masyarakat A')
        ->description->toBe('Deskripsi Pengabdian Masyarakat A')
        ->year->toBe(2022)
        ->funding_source->toBe('Sumber Dana A')
        ->budget->toBe(1000000.00);
});

it('throws an error when creating a community service without lecturer_id', function () {
    CommunityService::factory()->create(['lecturer_id' => null]);
})->throws(QueryException::class);

it('ensures that the budget is a number', function () {
    CommunityService::factory()->create(['budget' => 'not-a-number']);
})->throws(QueryException::class);

it('ensures community services can be deleted and lecturer not deleted', function () {
    $lecturer = Lecturer::factory()->create();
    $communityService = CommunityService::factory()->create(['lecturer_id' => $lecturer->id]);

    $communityService->delete();

    expect(Lecturer::find($lecturer->id))->not->toBeNull();
});
