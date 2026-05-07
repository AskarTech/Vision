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

    public function credit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $channel = 'platform_wallet',
        string $referenceType = null,
        ?int $referenceId = null,
        ?string $externalReference = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('Credit amount must be positive.');
        }

        $wallet->refresh();

        $currentBalance = (float) $wallet->balance;
        $newBalance = $currentBalance + $amount;

        $wallet->update([
            'balance' => $newBalance,
            'last_activity_at' => now(),
        ]);

        return WalletTransaction::query()->create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'type' => 'credit',
            'channel' => $channel,
            'status' => 'approved',
            'amount' => $amount,
            'balance_before' => $currentBalance,
            'balance_after' => $newBalance,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'external_reference' => $externalReference,
            'description' => $description,
            'processed_at' => now(),
        ]);
    }

    public function refund(
        Wallet $wallet,
        float $amount,
        string $description,
        string $referenceType,
        int $referenceId,
        ?string $externalReference = null
    ): WalletTransaction {
        return $this->credit(
            wallet: $wallet,
            amount: $amount,
            description: $description,
            channel: 'manual_admin',
            referenceType: $referenceType,
            referenceId: $referenceId,
            externalReference: $externalReference
        );
    }
}
