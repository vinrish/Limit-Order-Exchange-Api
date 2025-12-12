<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('retrieves profile for authenticated user', function (): void {
    $user = User::factory()->create(['balance' => '200.00']);

    Sanctum::actingAs($user);

    $response = $this->getJson('api/v1/profile');

    $response->assertOk()
        ->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'balance' => '200.00',
        ]);
});

it('blocks profile access when not authenticated', function (): void {
    $response = $this->getJson('api/v1/profile');

    $response->assertStatus(401);
});
