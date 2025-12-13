<?php

declare(strict_types=1);

use App\Actions\User\AuthenticateUser;
use App\Models\User;
use Illuminate\Validation\ValidationException;

it('authenticates a user successfully', function (): void {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $action = new AuthenticateUser();

    $result = $action->handle('john@example.com', 'secret123');

    expect($result->is($user))->toBeTrue();
});

it('fails authentication with wrong password', function (): void {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $action = new AuthenticateUser();

    expect(function () use ($action): void {
        $action->handle('john@example.com', 'wrong-password');
    })->toThrow(ValidationException::class, 'Invalid credentials provided.');
});

it('fails authentication when email does not exist', function (): void {
    $action = new AuthenticateUser();

    expect(function () use ($action): void {
        $action->handle('missing@example.com', 'password');
    })->toThrow(ValidationException::class, 'Invalid credentials provided.');
});
