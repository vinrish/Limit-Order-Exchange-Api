<?php

declare(strict_types=1);

use App\Models\Trade;

it('returns an array', function (): void {
    $trade = Trade::factory()->create()->fresh();

    expect(array_keys($trade->toArray()))->toBe([
        'id',
        'buy_order_id',
        'sell_order_id',
        'price',
        'amount',
        'usd_volume',
        'fee_usd',
        'created_at',
        'updated_at',
    ]);
});
