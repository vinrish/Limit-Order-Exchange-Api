<?php

declare(strict_types=1);

use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\LogoutController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'store']);
Route::post('login', [LoginController::class, 'store']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('logout', [LogoutController::class, 'store']);

    Route::get('profile', [ProfileController::class, 'show']);
});
