<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Enums\OrderStatus;
use App\Models\Asset;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class CancelOrderController
{
    public function store(Request $request, string $id)
    {
        $user = $request->user();

        return DB::transaction(function () use ($user, $id) {
            $order = Order::query()->where('id', $id)->lockForUpdate()->firstOrFail();
            if ($order->user_id !== $user->id) {
                return response()->json(['error' => 'forbidden'], 403);
            }

            if ($order->status !== OrderStatus::OPEN) {
                return response()->json(['error' => 'cannot cancel'], 422);
            }

            // cancel logic: release USD or assets depending side
            if ($order->side === 'buy') {
                $refund = bcmul((string) $order->price, (string) $order->amount, 8);
                $freshUser = User::query()->lockForUpdate()->find($user->id);
                $freshUser->update([
                    'balance' => bcadd((string) $freshUser->balance, $refund, 8),
                ]);
                $freshUser->save();
            } else {
                $asset = Asset::query()->where('user_id', $user->id)->where('symbol', $order->symbol)->lockForUpdate()->first();
                // move locked_amount back to amount
                $asset->update([
                    'locked_amount' => bcsub((string) $asset->locked_amount, (string) $order->amount, 18),
                ]);
                $asset->update([
                    'amount' => bcadd((string) $asset->amount, (string) $order->amount, 18),
                ]);
                $asset->save();
            }

            $order->update([
                'status' => OrderStatus::CANCELED,
            ]);

            $order->save();

            return response()->json(['ok' => true]);
        });
    }
}
