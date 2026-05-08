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

class CheckoutInsufficientStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_checkout_fails_when_stock_is_insufficient(): void
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
            'price' => 500,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ]);

        $user = User::query()->create([
            'name' => 'Test User',
            'phone' => '967770000099',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        Wallet::forceCreate([
            'user_id' => $user->id,
            'balance' => 5000,
            'points_balance' => 0,
            'status' => 'active',
        ]);

        Card::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'package_id' => $package->id,
            'code' => 'ONLY-ONE',
            'price' => 500,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/checkout/wallet', [
            'user_id' => $user->id,
            'idempotency_key' => 'stock-shortage',
            'items' => [
                ['package_id' => $package->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(422)->assertJson(['success' => false]);
        $this->assertDatabaseCount('card_orders', 0);
    }
}
