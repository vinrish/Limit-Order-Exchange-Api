<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;

it('returns an array', function (): void {
    $order = Order::factory()->create()->fresh();

    expect(array_keys($order->toArray()))->toBe([
        'id',
        'user_id',
        'symbol',
        'side',
        'price',
        'amount',
        'status',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a user', function (): void {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
    ])->fresh();

    expect($order->user->id)->toBe($user->id);
    expect($order->user)->toBeInstanceOf(User::class);
});
