<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;
use Throwable;

final readonly class CreateUser
{
    /**
     * @param  array<string, mixed>  $attributes
     *
     * @throws Throwable
     */
    public function handle(array $data, #[SensitiveParameter] string $password): User
    {
        return DB::transaction(function () use ($data, $password) {
            $user = User::query()->create([
                ...$data,
                'password' => Hash::make($password),
                'balance' => '0.00',
            ]);

            event(new Registered($user));

            return $user;
        });
    }
}
