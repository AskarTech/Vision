<?php

namespace App\Actions\Admin;

use App\Models\CardOrder;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RefundPaidOrderAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function execute(CardOrder $order): CardOrder
    {
        return DB::transaction(function () use ($order): CardOrder {
            $order->refresh();

            if ($order->status !== 'paid') {
                throw new RuntimeException(__('admin.order_refund_only_paid'));
            }

            $order->loadMissing(['items.card', 'user.wallet', 'walletTransaction']);

            if ($order->payment_channel !== 'platform_wallet') {
                throw new RuntimeException(__('admin.order_refund_wallet_only'));
            }

            $ledger = $order->walletTransaction;
            if (! $ledger) {
                throw new RuntimeException(__('admin.order_missing_wallet_tx'));
            }

            $wallet = $order->user?->wallet;
            if (! $wallet) {
                throw new RuntimeException(__('admin.customer_wallet_missing'));
            }

            $this->walletService->reverseCheckoutDebit(
                wallet: $wallet,
                cashPortion: (float) $ledger->amount,
                pointsPortion: (int) $ledger->points_amount,
                referenceType: CardOrder::class,
                referenceId: $order->id,
                description: __('admin.order_refund_ledger_description', ['id' => $order->id]),
            );

            foreach ($order->items as $item) {
                $card = $item->card;
                if ($card && $card->status === 'sold') {
                    $card->update([
                        'status' => 'active',
                        'sold_to_user_id' => null,
                        'sold_at' => null,
                    ]);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'meta' => array_merge($order->meta ?? [], [
                    'admin_refund_at' => now()->toDateTimeString(),
                ]),
            ]);

            return $order->fresh();
        });
    }
}
