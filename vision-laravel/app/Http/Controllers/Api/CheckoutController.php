<?php

namespace App\Http\Controllers\Api;

use App\Actions\Checkout\CheckoutWithWalletAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutWithWalletRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutWithWalletAction $checkoutWithWalletAction
    ) {
    }

    public function wallet(CheckoutWithWalletRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->findOrFail($validated['user_id']);
        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'User account is disabled.',
            ], 422);
        }

        try {
            $order = $this->checkoutWithWalletAction->execute(
                user: $user,
                items: $validated['items'],
                idempotencyKey: $validated['idempotency_key'],
                pointsToRedeem: (int) ($validated['points_to_redeem'] ?? 0),
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'wallet_transaction_id' => $order->wallet_transaction_id,
            'total_amount' => $order->total_amount,
            'cards' => $order->items->map(fn ($item): array => [
                'package_name' => $item->package_name,
                'card_code' => $item->card_code,
                'price' => $item->unit_price,
            ])->values(),
        ]);
    }
}
