<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $request->user()->currentAccessToken()?->delete();
        }

        return response()->json(['message' => 'Logged out successful']);
    }
}
