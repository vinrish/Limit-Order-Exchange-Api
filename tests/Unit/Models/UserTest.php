<?php

declare(strict_types=1);

use App\Models\User;

it('returns an array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'balance',
        'created_at',
        'updated_at',
    ]);
});
