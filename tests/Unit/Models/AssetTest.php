<?php

declare(strict_types=1);

use App\Models\Asset;
use Carbon\CarbonInterface;

it('returns an array', function (): void {
    $asset = Asset::factory()->create()->fresh();

    expect(array_keys($asset->toArray()))->toBe([
        'id',
        'user_id',
        'symbol',
        'amount',
        'locked_amount',
        'created_at',
        'updated_at',
    ]);
});

it('creates a valid asset object', function (): void {
    $asset = Asset::factory()->create()->fresh();
    expect($asset)->toBeInstanceOf(Asset::class)
        ->and($asset->id)->toBeString()
        ->and($asset->symbol)->toBeString()
        ->and($asset->created_at)->toBeInstanceOf(CarbonInterface::class)
        ->and($asset->updated_at)->toBeInstanceOf(CarbonInterface::class);
});

it('applies casts correctly', function (): void {
    $asset = Asset::factory()->create()->fresh();

    expect($asset->id)->toBeString()
        ->and($asset->created_at)->toBeInstanceOf(CarbonInterface::class)
        ->and($asset->updated_at)->toBeInstanceOf(CarbonInterface::class);
});
