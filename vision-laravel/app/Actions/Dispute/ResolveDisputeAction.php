<?php

namespace App\Actions\Dispute;

use App\Models\CardOrder;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ResolveDisputeAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function refund(CardOrder $order, string $reason): CardOrder
    {
        return DB::transaction(function () use ($order, $reason) {
            if ($order->status !== 'failed') {
                throw new \Exception('Order is not eligible for dispute refund');
            }

            $wallet = $order->user?->wallet;
            if (! $wallet) {
                throw new \Exception('Customer wallet not found');
            }

            $this->walletService->refund(
                wallet: $wallet,
                amount: (float) $order->total_amount,
                description: 'Order dispute refunded',
                referenceType: CardOrder::class,
                referenceId: $order->id
            );

            $order->update([
                'status' => 'cancelled',
                'meta' => array_merge($order->meta ?? [], [
                    'dispute_resolution' => 'refunded',
                    'dispute_reason' => $reason,
                    'resolved_at' => now()->toDateTimeString(),
                ]),
            ]);

            foreach ($order->items as $item) {
                if ($item->card) {
                    $item->card->update(['status' => 'active']);
                }
            }

            return $order->fresh();
        });
    }

    public function reject(CardOrder $order, string $reason): CardOrder
    {
        return DB::transaction(function () use ($order, $reason) {
            if ($order->status !== 'failed') {
                throw new \Exception('Order is not eligible for dispute resolution');
            }

            $order->update([
                'status' => 'paid',
                'meta' => array_merge($order->meta ?? [], [
                    'dispute_resolution' => 'rejected',
                    'dispute_reason' => $reason,
                    'resolved_at' => now()->toDateTimeString(),
                ]),
            ]);

            return $order->fresh();
        });
    }
}
