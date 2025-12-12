<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Actions\User\AuthenticateUser;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;

final class LoginController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginUserRequest $request, AuthenticateUser $action)
    {
        $user = $action->handle(
            $request->string('email')->lower()->value(),
            $request->string('password')->value(),
        );

        $user->tokens()->delete();

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'access_token' => $token,
        ]);
    }
}
