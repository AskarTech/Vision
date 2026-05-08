<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\GatewayCheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/checkout/wallet', [CheckoutController::class, 'wallet']);
    Route::post('/checkout/gateway/init', [GatewayCheckoutController::class, 'init']);
});
