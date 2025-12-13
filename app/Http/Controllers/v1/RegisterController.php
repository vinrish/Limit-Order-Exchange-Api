<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Actions\User\CreateUser;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class RegisterController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, CreateUser $action): JsonResponse
    {
        $attributes = $request->safe()->except('password');

        $user = $action->handle(
            $attributes,
            $request->string('password')->value(),
        );

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'User Registered successfully',
            'user' => new UserResource($user),
            'access_token' => $token,
        ], 201);
    }
}
