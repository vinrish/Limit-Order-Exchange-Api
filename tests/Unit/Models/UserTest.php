<?php

declare(strict_types=1);

use App\Models\Asset;
use App\Models\Order;
use App\Models\User;

it('returns an array', function (): void {
    $user = User::factory()->create([
        'balance' => '0.00000000',
    ])->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'balance',
        'created_at',
        'updated_at',
    ]);

    expect($user->balance)->toBeString();
    expect($user->balance)->toBe('0.00000000');

    expect($user)->not()->toHaveKey('password');
    expect($user)->not()->toHaveKey('remember_token');
});

it('can have assets and orders', function (): void {
    $user = User::factory()->create()->fresh();

    $asset = Asset::factory()->create([
        'user_id' => $user->id,
        'symbol' => 'BTC',
    ]);

    $user->refresh();

    expect($user->assets->pluck('id')->toArray())->toContain($asset->id);

    expect($user->asset('BTC')->first()->id)->toBe($asset->id);

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'symbol' => 'BTC',
        'amount' => '1.00000000',
        'price' => '100.00',
        'side' => 'buy',
    ]);

    $user->refresh();

    expect($user->orders->pluck('id')->toArray())->toContain($order->id);
});
