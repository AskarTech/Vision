<?php

namespace App\Actions\Withdrawal;

use App\Models\User;
use App\Models\Withdrawal;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ApproveWithdrawalAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function execute(Withdrawal $withdrawal, User $reviewer): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal, $reviewer) {
            if ($withdrawal->status !== 'pending') {
                throw new \Exception('Withdrawal request is not pending');
            }

            // Verify seller has sufficient balance
            $seller = $withdrawal->seller;
            $wallet = $seller->wallet;
            if (! $wallet) {
                throw new \Exception('Seller has no settlement wallet configured.');
            }

            if ($wallet->balance < $withdrawal->amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            $withdrawal->update([
                'status' => 'approved',
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

            // Debit seller wallet
            $this->walletService->debit(
                wallet: $wallet,
                amount: (float) $withdrawal->amount,
                description: 'Withdrawal request approved',
                referenceType: Withdrawal::class,
                referenceId: $withdrawal->id,
                externalReference: 'withdrawal_debit:'.$withdrawal->id,
            );

            return $withdrawal->fresh();
        });
    }
}
