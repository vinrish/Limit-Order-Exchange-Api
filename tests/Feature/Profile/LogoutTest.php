<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('logs out the user by deleting the current token', function (): void {
    $user = User::factory()->create()->fresh();

    Sanctum::actingAs($user);

    $this->postJson('api/v1/logout')
        ->assertOk()
        ->assertJson(['message' => 'Logged out successful']);

    expect($user->fresh()->tokens()->count())->toBe(0);
});
