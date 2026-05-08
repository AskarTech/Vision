<?php

namespace App\Actions\Withdrawal;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class RejectWithdrawalAction
{
    public function execute(Withdrawal $withdrawal, User $reviewer, ?string $reason = null): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal, $reviewer, $reason) {
            if ($withdrawal->status !== 'pending') {
                throw new \Exception('Withdrawal request is not pending');
            }

            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

            return $withdrawal->fresh();
        });
    }
}
