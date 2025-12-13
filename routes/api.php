<?php

declare(strict_types=1);

use App\Http\Controllers\v1\AllOrderController;
use App\Http\Controllers\v1\CancelOrderController;
use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\LogoutController;
use App\Http\Controllers\v1\OrderController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\RegisterController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'store']);
Route::post('login', [LoginController::class, 'store']);

Route::middleware('auth:sanctum')->group(function (): void {
    // Auth
    Route::post('logout', [LogoutController::class, 'store']);
    Route::get('profile', [ProfileController::class, 'show']);

    // Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::post('orders/{id}/cancel', [CancelOrderController::class, 'store']);
    Route::get('all-orders', [AllOrderController::class, 'index']);
});
