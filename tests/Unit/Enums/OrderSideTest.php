<?php

declare(strict_types=1);

use App\Enums\OrderSide;

it('returns all enum values', function (): void {
    expect(OrderSide::values())
        ->toBe([
            'buy',
            'sell',
        ]);
});

it('returns correct labels', function (): void {
    expect(OrderSide::BUY->label())->toBe('Buy')
        ->and(OrderSide::SELL->label())->toBe('Sell');
});

it('returns correct values', function (): void {
    expect(OrderSide::BUY->value)->toBe('buy')
        ->and(OrderSide::SELL->value)->toBe('sell');
});
