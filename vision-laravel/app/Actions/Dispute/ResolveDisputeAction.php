<?php

namespace App\Actions\Dispute;

use App\Models\CardOrder;
use Illuminate\Support\Facades\DB;

class ResolveDisputeAction
{
    public function refund(CardOrder $order, string $reason): CardOrder
    {
        return DB::transaction(function () use ($order, $reason) {
            if ($order->status !== 'disputed') {
                throw new \Exception('Order is not in disputed status');
            }

            // Refund customer wallet
            $order->customer->wallet->credit($order->total_amount);

            $order->update([
                'status' => 'refunded',
                'resolution_notes' => $reason,
                'resolved_at' => now(),
            ]);

            // Return card to available status if applicable
            if ($order->card) {
                $order->card->update(['status' => 'available']);
            }

            return $order->fresh();
        });
    }

    public function reject(CardOrder $order, string $reason): CardOrder
    {
        return DB::transaction(function () use ($order, $reason) {
            if ($order->status !== 'disputed') {
                throw new \Exception('Order is not in disputed status');
            }

            $order->update([
                'status' => 'completed',
                'resolution_notes' => $reason,
                'resolved_at' => now(),
            ]);

            return $order->fresh();
        });
    }
}
