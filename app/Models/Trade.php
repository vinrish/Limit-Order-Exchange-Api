<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property-read string $buy_order_id
 * @property-read string $sell_order_id
 * @property-read string $price
 * @property-read string $amount
 * @property-read string $usd_volume
 * @property-read string $fee_usd
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Trade extends Model
{
    /** @use HasFactory<\Database\Factories\TradeFactory> */
    use HasFactory, HasUuids;

    protected $table = 'trades';

    protected $guarded = [];

    public function casts(): array
    {
        return [
            'id' => 'string',
            'buy_order_id' => 'string',
            'sell_order_id' => 'string',
            'price' => 'decimal:8',
            'amount' => 'decimal:18',
            'usd_volume' => 'decimal:8',
            'fee_usd' => 'decimal:8',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
