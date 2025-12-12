<?php

declare(strict_types=1);

use App\Enums\OrderStatus;

it('returns all enum values', function (): void {
    expect(OrderStatus::values())
        ->toBe([
            1,
            2,
            3,
        ]);
});

it('returns correct labels for each enum case', function (): void {
    expect(OrderStatus::OPEN->label())->toBe('Open')
        ->and(OrderStatus::FILLED->label())->toBe('Filled')
        ->and(OrderStatus::CANCELED->label())->toBe('Canceled');
});

it('has proper enum values for cases', function (): void {
    expect(OrderStatus::OPEN->value)->toBe(1)
        ->and(OrderStatus::FILLED->value)->toBe(2)
        ->and(OrderStatus::CANCELED->value)->toBe(3);
});
