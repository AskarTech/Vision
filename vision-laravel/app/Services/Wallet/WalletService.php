<?php

namespace App\Services\Wallet;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use RuntimeException;

class WalletService
{
    public function debit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $referenceType,
        ?int $referenceId = null
    ): WalletTransaction {
        $wallet->refresh();

        $currentBalance = (float) $wallet->balance;
        if ($currentBalance < $amount) {
            throw new RuntimeException('Insufficient wallet balance.');
        }

        $newBalance = $currentBalance - $amount;

        $wallet->update([
            'balance' => $newBalance,
            'last_activity_at' => now(),
        ]);

        return WalletTransaction::query()->create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'type' => 'debit',
            'channel' => 'platform_wallet',
            'status' => 'approved',
            'amount' => $amount,
            'balance_before' => $currentBalance,
            'balance_after' => $newBalance,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'processed_at' => now(),
        ]);
    }
}
