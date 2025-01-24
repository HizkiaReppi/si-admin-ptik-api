<?php

test('new users can register', function () {
    $response = $this->post('/v1/auth/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'Password123@',
        'password_confirmation' => 'Password123@',
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(201);
});
