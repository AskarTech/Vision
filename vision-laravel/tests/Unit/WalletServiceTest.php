<?php

namespace Tests\Unit;

use App\Models\CardOrder;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_credit_is_idempotent_when_external_reference_repeats(): void
    {
        $user = User::factory()->create();
        Wallet::query()->create(['user_id' => $user->id, 'status' => 'active']);

        $wallet = $user->wallet()->firstOrFail();
        $service = app(WalletService::class);

        $first = $service->credit($wallet, 100.0, 'topup', 'manual_admin', null, null, 'idem-credit-1');
        $second = $service->credit($wallet, 100.0, 'topup', 'manual_admin', null, null, 'idem-credit-1');

        $this->assertSame($first->id, $second->id);
        $this->assertSame(100.0, (float) $wallet->fresh()->balance);
        $this->assertDatabaseCount('wallet_transactions', 1);
    }

    public function test_refund_creates_ledger_type_refund_and_is_idempotent(): void
    {
        $user = User::factory()->create();
        Wallet::query()->create(['user_id' => $user->id, 'status' => 'active']);

        $wallet = $user->wallet()->firstOrFail();
        $service = app(WalletService::class);

        $service->credit($wallet, 100.0, 'open', 'manual_admin', null, null, 'open-1');

        $r1 = $service->refund($wallet, 40.0, 'dispute', CardOrder::class, 42);
        $r2 = $service->refund($wallet, 40.0, 'dispute', CardOrder::class, 42);

        $this->assertSame('refund', $r1->type);
        $this->assertSame($r1->id, $r2->id);
        $this->assertSame(140.0, (float) $wallet->fresh()->balance);
    }

    public function test_ledger_chain_matches_balance_after_credit_and_debit(): void
    {
        $user = User::factory()->create();
        Wallet::query()->create(['user_id' => $user->id, 'status' => 'active']);

        $wallet = $user->wallet()->firstOrFail();
        $service = app(WalletService::class);

        $service->credit($wallet, 200.0, 'c', 'manual_admin', null, null, 'c1');
        $service->debit($wallet, 50.0, 'd', CardOrder::class, 1, 'd1');

        $this->assertTrue($service->ledgerChainMatchesBalance($wallet->fresh()));
    }
}
