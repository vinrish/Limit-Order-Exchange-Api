<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => (string) $this->balance,
            'assets' => $this->assets->map(fn ($asset): array => [
                'symbol' => $asset->symbol,
                'amount' => (string) $asset->amount,
                'locked_amount' => (string) $asset->locked_amount,
            ])->values(),
        ];
    }
}
