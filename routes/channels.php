<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// Broadcast::channel('App.Models.User.{id}', fn ($user, $id): bool => (int) $user->id === (int) $id);
Broadcast::channel('user.{id}', fn ($user, $id): bool => (int) $user->id === (int) $id);
