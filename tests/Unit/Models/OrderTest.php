<?php

declare(strict_types=1);

use App\Models\Order;

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
