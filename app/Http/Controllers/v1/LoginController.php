<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1;

use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class LoginController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginUserRequest $request)
    {
        $email = mb_strtolower((string) $request->input('email'));
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'access_token' => $token,
        ]);
    }
}
