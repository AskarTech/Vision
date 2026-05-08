<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Stage 6 placeholder: external PSP initiation until signed callbacks + adapters ship.
 */
class GatewayCheckoutController extends Controller
{
    public function init(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'gateway' => ['required', 'string', 'max:40'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.package_id' => ['required', 'integer', 'exists:packages,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return response()->json([
            'success' => false,
            'code' => 'gateway_stub',
            'message' => 'External gateway checkout is not wired. Use POST /api/v1/checkout/wallet or configure a payment driver.',
            'configured_gateways' => array_keys(config('marketplace.payment_gateways', [])),
        ], 503);
    }
}
