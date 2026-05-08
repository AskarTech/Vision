<?php

namespace App\Actions\Topup;

use App\Models\TopupRequest;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class ApproveTopupAction
{
    public function __construct(
        private WalletService $walletService
    ) {}

    public function execute(TopupRequest $topup, User $reviewer): TopupRequest
    {
        return DB::transaction(function () use ($topup, $reviewer) {
            if ($topup->status !== 'pending') {
                throw new \Exception('Topup request is not pending');
            }

            $topup->update([
                'status' => 'approved',
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

            // Credit user wallet
            $this->walletService->credit(
                wallet: $topup->user->wallet,
                amount: (float) $topup->amount,
                description: 'Topup request approved',
                channel: 'manual_admin',
                referenceType: TopupRequest::class,
                referenceId: $topup->id,
                externalReference: 'topup_approved:'.$topup->id,
                meta: $topup->reference_code ? ['reference_code' => $topup->reference_code] : null,
            );

            return $topup->fresh();
        });
    }
}
