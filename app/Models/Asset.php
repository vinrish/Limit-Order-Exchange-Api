<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            'amount' => 'string',
            'locked_amount' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
