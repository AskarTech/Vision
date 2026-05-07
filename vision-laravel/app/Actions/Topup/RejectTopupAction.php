<?php

namespace App\Actions\Topup;

use App\Models\TopupRequest;
use Illuminate\Support\Facades\DB;

class RejectTopupAction
{
    public function execute(TopupRequest $topup, ?string $reason = null): TopupRequest
    {
        return DB::transaction(function () use ($topup, $reason) {
            if ($topup->status !== 'pending') {
                throw new \Exception('Topup request is not pending');
            }

            $topup->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            return $topup->fresh();
        });
    }
}
