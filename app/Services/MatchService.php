<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Events\OrderMatched;
use App\Models\Asset;
use App\Models\Order;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class MatchService
{
    private string $commissionRate = '0.015'; // 1.5%

    public function matchOrder(Order $order): ?Trade
    {
        Log::info('MatchService triggered', ['order_id' => $order->id]);

        return DB::transaction(function () use ($order) {

            $order = Order::query()
                ->lockForUpdate()
                ->find($order->id);

            if (! $order || $order->status !== OrderStatus::OPEN) {
                return null;
            }

            // Find counter order
            $counter = $order->side === OrderSide::BUY
                ? Order::query()
                    ->where('status', OrderStatus::OPEN)
                    ->where('side', OrderSide::SELL)
                    ->where('symbol', $order->symbol)
                    ->where('price', '<=', $order->price)
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->first()
                : Order::query()
                    ->where('status', OrderStatus::OPEN)
                    ->where('side', OrderSide::BUY)
                    ->where('symbol', $order->symbol)
                    ->where('price', '>=', $order->price)
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->first();

            if (! $counter) {
                Log::info('No counter order found');

                return null;
            }

            // Full-fill only
            if (bccomp($order->amount, $counter->amount, 18) !== 0) {
                Log::info('Amount mismatch');

                return null;
            }

            $buy = $order->side === OrderSide::BUY ? $order : $counter;
            $sell = $order->side === OrderSide::SELL ? $order : $counter;

            $tradePrice = $counter->created_at <= $order->created_at
                ? $counter->price
                : $order->price;

            $amount = $order->amount;
            $usdVolume = bcmul($tradePrice, $amount, 8);
            $fee = bcmul($usdVolume, $this->commissionRate, 8);

            $buyer = User::lockForUpdate()->find($buy->user_id);
            $seller = User::lockForUpdate()->find($sell->user_id);

            $sellerAsset = Asset::query()
                ->where('user_id', $seller->id)
                ->where('symbol', $sell->symbol)
                ->lockForUpdate()
                ->first();

            if (! $sellerAsset || bccomp($sellerAsset->locked_amount, $amount, 18) < 0) {
                return null;
            }

            $buyerAsset = Asset::firstOrCreate(
                ['user_id' => $buyer->id, 'symbol' => $buy->symbol],
                ['amount' => '0', 'locked_amount' => '0']
            );

            $buyerAsset = Asset::query()
                ->where('user_id', $buyer->id)
                ->where('symbol', $buy->symbol)
                ->lockForUpdate()
                ->first();

            // === SETTLEMENT ===

            $sellerAsset->update([
                'locked_amount' => bcsub((string) $sellerAsset->locked_amount, (string) $amount, 18),
            ]);

            $sellerAsset->update([
                'locked_amount' => bcsub((string) $sellerAsset->locked_amount, (string) $amount, 18),
            ]);

            $seller->balance = bcadd(
                $seller->balance,
                bcsub($usdVolume, $fee, 8),
                8
            );
            $seller->save();

            $buy->update([
                'status' => OrderStatus::FILLED,
            ]);

            $sell->update([
                'status' => OrderStatus::FILLED,
            ]);
            $buy->save();
            $sell->save();

            $trade = Trade::create([
                'buy_order_id' => $buy->id,
                'sell_order_id' => $sell->id,
                'price' => $tradePrice,
                'amount' => $amount,
                'usd_volume' => $usdVolume,
                'fee_usd' => $fee,
            ]);

            broadcast(new OrderMatched(
                trade: $trade,
                userId: $buyer->id,
                payload: [
                    'base_symbol' => $buy->symbol,
                    'quote_symbol' => 'USD',
                    'base_delta' => (string) $amount,
                    'quote_delta' => '-'.$usdVolume,
                ]
            ))->toOthers();

            broadcast(new OrderMatched(
                trade: $trade,
                userId: $seller->id,
                payload: [
                    'base_symbol' => $sell->symbol,
                    'quote_symbol' => 'USD',
                    'base_delta' => '-'.$amount,
                    'quote_delta' => bcsub($usdVolume, $fee, 8),
                ]
            ))->toOthers();

            Log::info('Trade executed', ['trade_id' => $trade->id]);

            return $trade;
        });
    }
}
