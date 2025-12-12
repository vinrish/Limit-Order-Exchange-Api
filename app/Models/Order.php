<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property-read string $uuid
 * @property-read string $symbol
 * @property-read string $side
 * @property-read string $price
 * @property-read string $amount
 * @property-read int $status
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, HasUuids;

    protected $table = 'orders';

    protected $guarded = [];

    public function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'string',
            'side' => OrderSide::class,
            'price' => 'decimal:8',
            'amount' => 'decimal:18',
            'status' => OrderStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
