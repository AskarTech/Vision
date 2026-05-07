<?php

namespace App\Actions\Withdrawal;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class RejectWithdrawalAction
{
    public function execute(Withdrawal $withdrawal, ?string $reason = null): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal, $reason) {
            if ($withdrawal->status !== 'pending') {
                throw new \Exception('Withdrawal request is not pending');
            }

            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            return $withdrawal->fresh();
        });
    }
}
