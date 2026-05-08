<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutWithPointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'marketplace.points.cash_equivalent_per_1000' => 1000,
        ]);
    }

    public function test_checkout_can_combine_points_and_cash(): void
    {
        $seller = Seller::query()->create([
            'name' => 'Vision Seller',
            'slug' => 'vision-seller',
            'status' => 'active',
        ]);

        $network = Network::query()->create([
            'seller_id' => $seller->id,
            'name' => 'Yemen Net',
            'slug' => 'yemen-net',
            'status' => 'active',
        ]);

        $package = Package::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'name' => '10GB Monthly',
            'price' => 1000,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ]);

        $user = User::query()->create([
            'name' => 'Test User',
            'phone' => '967770000088',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        Wallet::forceCreate([
            'user_id' => $user->id,
            'balance' => 400,
            'points_balance' => 700,
            'status' => 'active',
        ]);

        Card::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'package_id' => $package->id,
            'code' => 'CARD-PTS',
            'price' => 1000,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/checkout/wallet', [
            'user_id' => $user->id,
            'idempotency_key' => 'pts-checkout-1',
            'points_to_redeem' => 700,
            'items' => [
                ['package_id' => $package->id, 'quantity' => 1],
            ],
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $wallet = Wallet::query()->where('user_id', $user->id)->first();
        $this->assertSame(100.0, (float) $wallet->balance);
        $this->assertSame(0, (int) $wallet->points_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'points_amount' => 700,
        ]);
    }
}
