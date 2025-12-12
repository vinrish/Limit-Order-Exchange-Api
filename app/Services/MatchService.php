<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OrderMatched;
use App\Models\Asset;
use App\Models\Order;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class MatchService
{
    private $commissionRate = '0.015'; // 1.5%

    public function matchOrder(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $order = Order::query()->lockForUpdate()->find($order->id);

            if (! $order || $order->status !== 1) {
                return;
            }

            if ($order->side === 'buy') {
                $counter = Order::query()->where('status', 1)
                    ->where('side', 'sell')
                    ->where('symbol', $order->symbol)
                    ->where('price', '<=', $order->price)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->first();
            } else {
                $counter = Order::query()->where('status', 1)
                    ->where('side', 'buy')
                    ->where('symbol', $order->symbol)
                    ->where('price', '>=', $order->price)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->first();
            }

            if (! $counter) {
                return;
            }

            if (bccomp((string) $order->amount, (string) $counter->amount, 18) !== 0) {
                return;
            }

            $buy = $order->side === 'buy' ? $order : $counter;
            $sell = $order->side === 'sell' ? $order : $counter;

            $tradePrice = $counter->created_at <= $order->created_at ? $counter->price : $order->price;

            $amount = $order->amount;

            $usd_volume = bcmul((string) $tradePrice, (string) $amount, 8);

            $fee = bcmul($usd_volume, (string) $this->commissionRate, 8);

            $buyerUser = User::query()->lockForUpdate()->find($buy->user_id);
            $sellerUser = User::query()->lockForUpdate()->find($sell->user_id);

            $sellerAsset = Asset::query()->where('user_id', $sellerUser->id)
                ->where('symbol', $sell->symbol)
                ->lockForUpdate()
                ->first();

            if (! $sellerAsset || bccomp((string) $sellerAsset->locked_amount, (string) $amount, 18) < 0) {
                // inconsistent state
                return;
            }

            // buyer's asset row (create if missing)
            $buyerAsset = Asset::query()->firstOrCreate(
                ['user_id' => $buyerUser->id, 'symbol' => $buy->symbol],
                ['amount' => '0', 'locked_amount' => '0']
            );
            $buyerAsset = Asset::query()->where('user_id', $buyerUser->id)->where('symbol', $buy->symbol)->lockForUpdate()->first();

            $sellerAsset->update([
                'locked_amount' => bcsub((string) $sellerAsset->locked_amount, (string) $amount, 18),
            ]);
            $sellerAsset->save();

            $buyerAsset->update([
                'amount' => bcadd((string) $buyerAsset->amount, (string) $amount, 18),
            ]);
            $buyerAsset->save();

            $sellerCredit = bcsub($usd_volume, $fee, 8);
            $sellerUser->update([
                'balance' => bcadd((string) $sellerUser->balance, $sellerCredit, 8),
            ]);
            $sellerUser->save();

            $buy->update([
                'status' => 2,
            ]);
            $buy->save();
            $sell->update([
                'status' => 2,
            ]);
            $sell->save();

            $trade = Trade::query()->create([
                'buy_order_id' => $buy->id,
                'sell_order_id' => $sell->id,
                'price' => $tradePrice,
                'amount' => $amount,
                'usd_volume' => $usd_volume,
                'fee_usd' => $fee,
            ]);

            broadcast(new OrderMatched($trade, $buyerUser->id, $sellerUser->id))->toOthers();

            return $trade;
        }, 5);
    }
}
