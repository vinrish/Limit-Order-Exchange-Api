<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;

final class ProfileController
{
    public function show(): JsonResponse
    {
        $user = auth()->user()->load('assets');

        return response()->json(new ProfileResource($user));
    }
}
