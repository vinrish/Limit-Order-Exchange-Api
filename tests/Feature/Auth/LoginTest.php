<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('logs in successfully and receives a token', function (): void {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->postJson('api/v1/login', [
        'email' => 'jane@example.com',
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email', 'balance'],
            'access_token',
        ]);

    expect($user->tokens()->count())->toBe(1);
});

it('rejects invalid login credentials', function (): void {
    $response = $this->postJson('api/v1/login', [
        'email' => 'missing@example.com',
        'password' => 'wrong',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});
