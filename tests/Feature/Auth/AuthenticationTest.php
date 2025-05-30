<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(200);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'web');

    $response = $this->postJson('/v1/auth/logout', [], [
        'Accept' => 'application/json',
    ]);

    $this->assertGuest('web');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'code' => 200,
            'message' => 'User logged out successfully.',
        ]);
});
