<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Models\Asset;
use App\Models\Order;
use App\Models\User;
use App\Services\MatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class OrderController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $symbol = $request->query('symbol');
        $query = Order::query()
            ->where('status', 1)
            ->where('user_id', $user->id);

        if ($symbol) {
            $query->where('symbol', mb_strtoupper($symbol));
        }
        $orders = $query->orderBy('created_at', 'asc')->get();

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'symbol' => ['required', 'string'],
            'side' => ['required', Rule::in(['buy', 'sell'])],
            'price' => 'required|numeric|min:0.00000001',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $user = $request->user();
        $symbol = mb_strtoupper((string) $data['symbol']);
        $side = $data['side'];
//        $price = $data['price'];
//        $amount = $data['amount'];

        $price = bcadd((string)$data['price'], '0', 8);
        $amount = bcadd((string)$data['amount'], '0', 18);

        return DB::transaction(function () use ($user, $symbol, $side, $price, $amount) {
            if ($side === 'buy') {

                $cost = bcmul($price, $amount, 8);

                $freshUser = User::lockForUpdate()->find($user->id);
                if (bccomp((string) $freshUser->balance, $cost, 8) < 0) {
                    return response()->json(['error' => 'insufficient USD balance'], 422);
                }

                $freshUser->balance = bcsub((string) $freshUser->balance, $cost, 8);
                $freshUser->save();
            } else {
                $asset = Asset::where('user_id', $user->id)
                    ->where('symbol', $symbol)
                    ->lockForUpdate()
                    ->first();
                if (!$asset) {
                    return response()->json(['error' => 'asset not found'], 422);
                }

                $availableAmount = bcadd((string)$asset->amount, '0', 18);

                if (bccomp($availableAmount, $amount, 18) < 0) {
                    return response()->json([
                        'error' => 'insufficient asset balance',
                        'available' => $availableAmount
                    ], 422);
                }

                $asset->amount = bcsub($availableAmount, $amount, 18);
                $asset->locked_amount = bcadd((string)$asset->locked_amount, $amount, 18);
                $asset->save();
            }

            $order = Order::query()->create([
                'user_id' => $user->id,
                'symbol' => $symbol,
                'side' => $side,
                'price' => $price,
                'amount' => $amount,
                'status' => 1,
            ]);

            app(MatchService::class)->matchOrder($order);

            return response()->json($order->fresh());
        });
    }
}
