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

class CheckoutWithWalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_checkout_cards_with_wallet_balance(): void
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
            'phone' => '967770000001',
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
            'code' => 'CARD-001',
            'price' => 1000,
            'status' => 'active',
        ]);

        Card::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'package_id' => $package->id,
            'code' => 'CARD-002',
            'price' => 1000,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/checkout/wallet', [
            'user_id' => $user->id,
            'idempotency_key' => 'checkout-001',
            'items' => [
                ['package_id' => $package->id, 'quantity' => 2],
            ],
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
            'balance' => 3000,
        ]);

        $this->assertDatabaseCount('card_orders', 1);
        $this->assertDatabaseCount('card_order_items', 2);
        $this->assertDatabaseCount('wallet_transactions', 1);

        $this->assertDatabaseHas('cards', ['code' => 'CARD-001', 'status' => 'sold']);
        $this->assertDatabaseHas('cards', ['code' => 'CARD-002', 'status' => 'sold']);
        $this->assertDatabaseHas('cards', [
            'code' => 'CARD-001',
            'reserved_by_user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'code' => 'CARD-002',
            'reserved_by_user_id' => $user->id,
        ]);
    }

    public function test_checkout_with_same_idempotency_key_is_processed_once(): void
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
            'phone' => '967770000002',
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
            'code' => 'CARD-101',
            'price' => 1000,
            'status' => 'active',
        ]);

        $payload = [
            'user_id' => $user->id,
            'idempotency_key' => 'checkout-retry-001',
            'items' => [
                ['package_id' => $package->id, 'quantity' => 1],
            ],
        ];

        $firstResponse = $this->postJson('/api/v1/checkout/wallet', $payload);
        $secondResponse = $this->postJson('/api/v1/checkout/wallet', $payload);

        $firstResponse->assertOk()->assertJson(['success' => true]);
        $secondResponse->assertOk()->assertJson(['success' => true]);

        $firstOrderId = $firstResponse->json('order_id');
        $secondOrderId = $secondResponse->json('order_id');

        $this->assertSame($firstOrderId, $secondOrderId);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
            'balance' => 4000,
        ]);

        $this->assertDatabaseCount('card_orders', 1);
        $this->assertDatabaseCount('card_order_items', 1);
        $this->assertDatabaseCount('wallet_transactions', 1);
        $this->assertDatabaseHas('cards', [
            'code' => 'CARD-101',
            'status' => 'sold',
            'reserved_by_user_id' => $user->id,
        ]);
    }
}
