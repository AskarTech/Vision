<?php

namespace App\Actions\Topup;

use App\Models\TopupRequest;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ApproveTopupAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function execute(TopupRequest $topup): TopupRequest
    {
        return DB::transaction(function () use ($topup) {
            if ($topup->status !== 'pending') {
                throw new \Exception('Topup request is not pending');
            }

            $topup->update(['status' => 'approved']);

            // Credit user wallet
            $this->walletService->credit(
                $topup->user->wallet,
                $topup->amount,
                'topup_approved',
                [
                    'topup_request_id' => $topup->id,
                    'payment_gateway' => $topup->payment_gateway,
                    'transaction_ref' => $topup->transaction_ref,
                ]
            );

            return $topup->fresh();
        });
    }
}
