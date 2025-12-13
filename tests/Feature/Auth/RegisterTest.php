<?php

declare(strict_types=1);

use App\Models\User;

it('registers a user successfully', function (): void {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ];

    $response = $this->postJson('api/v1/register', $payload);

    $response->assertCreated()
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email', 'balance'],
            'access_token',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);

    expect(User::first()->balance)->toBe('0.00000000');
});

it('rejects invalid registration data', function (): void {
    $response = $this->postJson('api/v1/register', [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
        'password_confirmation' => 'wrong',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});
