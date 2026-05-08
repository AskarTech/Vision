<?php

namespace App\Actions\Topup;

use App\Models\TopupRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RejectTopupAction
{
    public function execute(TopupRequest $topup, User $reviewer, ?string $reason = null): TopupRequest
    {
        return DB::transaction(function () use ($topup, $reviewer, $reason) {
            if ($topup->status !== 'pending') {
                throw new \Exception('Topup request is not pending');
            }

            $topup->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

            return $topup->fresh();
        });
    }
}
