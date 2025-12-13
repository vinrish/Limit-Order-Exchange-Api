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
        $symbol = $request->query('symbol');
        $query = Order::query()->where('status', 1);
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

        $price = number_format((float)$data['price'], 8, '.', '');
        $amount = number_format((float)$data['amount'], 8, '.', '');

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
                $asset = Asset::where('user_id', $user->id)->where('symbol', $symbol)->lockForUpdate()->first();
                if (! $asset || bccomp((string) $asset->amount, (string) $amount, 18) < 0) {
                    return response()->json(['error' => 'insufficient asset balance'], 422);
                }
                $asset->amount = bcsub((string) $asset->amount, (string) $amount, 18);
                $asset->locked_amount = bcadd((string) $asset->locked_amount, (string) $amount, 18);
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
