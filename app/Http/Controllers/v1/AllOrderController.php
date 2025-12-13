<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Models\Order;
use Illuminate\Http\Request;

final class AllOrderController
{
    public function index(Request $request)
    {
        return Order::query()
            ->where('user_id', $request->user()->id)
            ->when(
                $request->filled('symbol'),
                fn ($q) => $q->where('symbol', $request->symbol)
            )
            ->orderByDesc('created_at')
            ->get();
    }
}
