<?php

namespace Tests\Feature;

use App\Models\TopupRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Policies\TopupRequestPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopupRequestPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_approve_pending_topup(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $wallet = Wallet::query()->create(['user_id' => $customer->id, 'status' => 'active']);

        $topup = TopupRequest::query()->create([
            'user_id' => $customer->id,
            'wallet_id' => $wallet->id,
            'method' => 'bank_transfer',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $policy = new TopupRequestPolicy;

        $this->assertFalse($policy->approve($customer, $topup));
        $this->assertTrue($policy->approve($admin, $topup));
    }
}
