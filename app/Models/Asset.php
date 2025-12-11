<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property-read string $user_id
 * @property-read string $symbol
 * @property-read string $amount
 * @property-read string $locked_amount
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory, HasUUIDs;

    protected $table = 'assets';

    protected $guarded = [];

    public function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'string',
            'symbol' => 'string',
            'amount' => 'decimal:18',
            'location' => 'decimal:18',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
