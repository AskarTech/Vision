<?php

namespace App\Services\Wallet;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WalletService
{
    /**
     * Debit cash balance. Optional external_reference enables idempotent retries (globally unique on ledger).
     *
     * @param  array<string, mixed>|null  $meta  Merged into ledger row meta (tracing, correlation).
     */
    public function debit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $referenceType,
        ?int $referenceId = null,
        ?string $externalReference = null,
        ?array $meta = null,
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('Debit amount must be positive.');
        }

        return DB::transaction(function () use ($wallet, $amount, $description, $referenceType, $referenceId, $externalReference, $meta): WalletTransaction {
            $wallet = $this->lockWallet($wallet);

            if ($externalReference !== null) {
                $existing = WalletTransaction::query()
                    ->where('external_reference', $externalReference)
                    ->first();

                if ($existing instanceof WalletTransaction) {
                    $this->assertSameWallet($existing, $wallet);

                    return $existing;
                }
            }

            $currentBalance = (float) $wallet->balance;
            if ($currentBalance < $amount) {
                throw new RuntimeException('Insufficient wallet balance.');
            }

            $newBalance = $currentBalance - $amount;

            Wallet::query()->whereKey($wallet->id)->update([
                'balance' => $newBalance,
                'last_activity_at' => now(),
            ]);

            $transaction = WalletTransaction::query()->create([
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
                'external_reference' => $externalReference,
                'description' => $description,
                'meta' => $meta,
                'processed_at' => now(),
            ]);

            Log::info('wallet.debit', [
                'wallet_transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
            ]);

            return $transaction;
        });
    }

    /**
     * Credit cash balance.
     *
     * @param  array<string, mixed>|null  $meta
     */
    public function credit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $channel = 'platform_wallet',
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $externalReference = null,
        ?array $meta = null,
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('Credit amount must be positive.');
        }

        return DB::transaction(function () use ($wallet, $amount, $description, $channel, $referenceType, $referenceId, $externalReference, $meta): WalletTransaction {
            $wallet = $this->lockWallet($wallet);

            if ($externalReference !== null) {
                $existing = WalletTransaction::query()
                    ->where('external_reference', $externalReference)
                    ->first();

                if ($existing instanceof WalletTransaction) {
                    $this->assertSameWallet($existing, $wallet);

                    return $existing;
                }
            }

            $currentBalance = (float) $wallet->balance;
            $newBalance = $currentBalance + $amount;

            Wallet::query()->whereKey($wallet->id)->update([
                'balance' => $newBalance,
                'last_activity_at' => now(),
            ]);

            $transaction = WalletTransaction::query()->create([
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
                'meta' => $meta,
                'processed_at' => now(),
            ]);

            Log::info('wallet.credit', [
                'wallet_transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'channel' => $channel,
                'external_reference' => $externalReference,
            ]);

            return $transaction;
        });
    }

    /**
     * Customer compensation: increases balance with ledger type `refund` (not a generic credit).
     *
     * @param  array<string, mixed>|null  $meta
     */
    public function refund(
        Wallet $wallet,
        float $amount,
        string $description,
        string $referenceType,
        int $referenceId,
        ?string $externalReference = null,
        ?array $meta = null,
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('Refund amount must be positive.');
        }

        $externalReference ??= $this->defaultRefundExternalReference($referenceType, $referenceId);

        return DB::transaction(function () use ($wallet, $amount, $description, $referenceType, $referenceId, $externalReference, $meta): WalletTransaction {
            $wallet = $this->lockWallet($wallet);

            $existing = WalletTransaction::query()
                ->where('external_reference', $externalReference)
                ->first();

            if ($existing instanceof WalletTransaction) {
                $this->assertSameWallet($existing, $wallet);

                return $existing;
            }

            $currentBalance = (float) $wallet->balance;
            $newBalance = $currentBalance + $amount;

            Wallet::query()->whereKey($wallet->id)->update([
                'balance' => $newBalance,
                'last_activity_at' => now(),
            ]);

            $transaction = WalletTransaction::query()->create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'type' => 'refund',
                'channel' => 'manual_admin',
                'status' => 'approved',
                'amount' => $amount,
                'balance_before' => $currentBalance,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
                'description' => $description,
                'meta' => $meta,
                'processed_at' => now(),
            ]);

            Log::info('wallet.refund', [
                'wallet_transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
            ]);

            return $transaction;
        });
    }

    /**
     * Reverse a `debitForPurchase` row: restore cash balance and points in one idempotent ledger entry.
     */
    public function reverseCheckoutDebit(
        Wallet $wallet,
        float $cashPortion,
        int $pointsPortion,
        string $referenceType,
        int $referenceId,
        string $description,
        ?string $externalReference = null,
    ): WalletTransaction {
        $cashPortion = round($cashPortion, 2);
        $pointsPortion = max(0, $pointsPortion);

        if ($cashPortion <= 0 && $pointsPortion <= 0) {
            throw new RuntimeException('Nothing to reverse on wallet.');
        }

        $externalReference ??= 'admin_reverse_checkout:'.class_basename($referenceType).':'.$referenceId;

        return DB::transaction(function () use ($wallet, $cashPortion, $pointsPortion, $referenceType, $referenceId, $description, $externalReference): WalletTransaction {
            $wallet = $this->lockWallet($wallet);

            $existing = WalletTransaction::query()
                ->where('external_reference', $externalReference)
                ->first();

            if ($existing instanceof WalletTransaction) {
                $this->assertSameWallet($existing, $wallet);

                return $existing;
            }

            $currentBalance = (float) $wallet->balance;
            $currentPoints = (int) $wallet->points_balance;

            $newBalance = round($currentBalance + $cashPortion, 2);
            $newPoints = $currentPoints + $pointsPortion;

            Wallet::query()->whereKey($wallet->id)->update([
                'balance' => $newBalance,
                'points_balance' => $newPoints,
                'last_activity_at' => now(),
            ]);

            $transaction = WalletTransaction::query()->create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'type' => 'refund',
                'channel' => 'manual_admin',
                'status' => 'approved',
                'amount' => $cashPortion,
                'points_amount' => $pointsPortion,
                'balance_before' => $currentBalance,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
                'description' => $description,
                'meta' => [
                    'cash_restored' => $cashPortion,
                    'points_restored' => $pointsPortion,
                ],
                'processed_at' => now(),
            ]);

            Log::info('wallet.reverse_checkout', [
                'wallet_transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'cash_portion' => $cashPortion,
                'points_portion' => $pointsPortion,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
            ]);

            return $transaction;
        });
    }

    /**
     * Checkout debit: cash portion from balance plus optional points redemption (single ledger row).
     *
     * Ledger `amount` reflects cash moved; `points_amount` reflects points consumed.
     *
     * @param  array<string, mixed>|null  $meta
     */
    public function debitForPurchase(
        Wallet $wallet,
        float $cashPortion,
        int $pointsPortion,
        string $description,
        string $referenceType,
        int $referenceId,
        ?string $externalReference = null,
        ?array $meta = null,
    ): WalletTransaction {
        $cashPortion = round($cashPortion, 2);
        $pointsPortion = max(0, $pointsPortion);

        if ($cashPortion < 0) {
            throw new RuntimeException('Invalid checkout cash portion.');
        }

        if ($cashPortion <= 0 && $pointsPortion <= 0) {
            throw new RuntimeException('Checkout debit must include cash or points.');
        }

        return DB::transaction(function () use ($wallet, $cashPortion, $pointsPortion, $description, $referenceType, $referenceId, $externalReference, $meta): WalletTransaction {
            $wallet = $this->lockWallet($wallet);

            if ($externalReference !== null) {
                $existing = WalletTransaction::query()
                    ->where('external_reference', $externalReference)
                    ->first();

                if ($existing instanceof WalletTransaction) {
                    $this->assertSameWallet($existing, $wallet);

                    return $existing;
                }
            }

            $currentBalance = (float) $wallet->balance;
            $currentPoints = (int) $wallet->points_balance;

            if ($cashPortion > 0 && $currentBalance < $cashPortion) {
                throw new RuntimeException('Insufficient wallet balance.');
            }

            if ($pointsPortion > 0 && $currentPoints < $pointsPortion) {
                throw new RuntimeException('Insufficient points balance.');
            }

            $newBalance = round($currentBalance - $cashPortion, 2);
            $newPoints = $currentPoints - $pointsPortion;

            Wallet::query()->whereKey($wallet->id)->update([
                'balance' => $newBalance,
                'points_balance' => $newPoints,
                'last_activity_at' => now(),
            ]);

            $transaction = WalletTransaction::query()->create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'type' => 'debit',
                'channel' => 'platform_wallet',
                'status' => 'approved',
                'amount' => $cashPortion,
                'points_amount' => $pointsPortion,
                'balance_before' => $currentBalance,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
                'description' => $description,
                'meta' => $meta,
                'processed_at' => now(),
            ]);

            Log::info('wallet.debit_checkout', [
                'wallet_transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'cash_portion' => $cashPortion,
                'points_portion' => $pointsPortion,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'external_reference' => $externalReference,
            ]);

            return $transaction;
        });
    }

    /**
     * True when approved ledger rows chain contiguously and end at the current wallet balance.
     */
    public function ledgerChainMatchesBalance(Wallet $wallet): bool
    {
        $wallet->refresh();

        $transactions = $wallet->transactions()
            ->where('status', 'approved')
            ->orderBy('id')
            ->get(['id', 'balance_before', 'balance_after']);

        if ($transactions->isEmpty()) {
            return abs((float) $wallet->balance) < 0.009;
        }

        $expectedPreviousAfter = null;

        foreach ($transactions as $transaction) {
            $before = (float) $transaction->balance_before;
            $after = (float) $transaction->balance_after;

            if ($expectedPreviousAfter !== null && abs($before - $expectedPreviousAfter) > 0.009) {
                return false;
            }

            $expectedPreviousAfter = $after;
        }

        return abs($expectedPreviousAfter - (float) $wallet->balance) < 0.009;
    }

    private function lockWallet(Wallet $wallet): Wallet
    {
        return Wallet::query()->whereKey($wallet->getKey())->lockForUpdate()->firstOrFail();
    }

    private function assertSameWallet(WalletTransaction $transaction, Wallet $wallet): void
    {
        if ((int) $transaction->wallet_id !== (int) $wallet->id) {
            throw new RuntimeException('Idempotency reference belongs to another wallet.');
        }
    }

    private function defaultRefundExternalReference(string $referenceType, int $referenceId): string
    {
        return 'ledger_refund:'.class_basename($referenceType).':'.$referenceId;
    }
}
