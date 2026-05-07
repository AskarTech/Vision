<?php

namespace App\Actions\Withdrawal;

use App\Models\Withdrawal;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ApproveWithdrawalAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function execute(Withdrawal $withdrawal): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal) {
            if ($withdrawal->status !== 'pending') {
                throw new \Exception('Withdrawal request is not pending');
            }

            // Verify seller has sufficient balance
            $seller = $withdrawal->seller;
            if ($seller->wallet->balance < $withdrawal->amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            $withdrawal->update(['status' => 'approved']);

            // Debit seller wallet
            $this->walletService->debit(
                $seller->wallet,
                $withdrawal->amount,
                'withdrawal_approved',
                [
                    'withdrawal_id' => $withdrawal->id,
                    'bank_name' => $withdrawal->bank_name,
                    'account_number' => $withdrawal->account_number,
                ]
            );

            return $withdrawal->fresh();
        });
    }
}
