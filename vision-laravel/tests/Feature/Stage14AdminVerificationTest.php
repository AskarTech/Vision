<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\CardOrder;
use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\TopupRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Stage14AdminVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_seller_manager_cannot_access_admin_area(): void
    {
        $sellerManager = User::factory()->create([
            'role' => 'seller_manager',
            'status' => 'active',
        ]);

        $this->actingAs($sellerManager);

        $this->get('/admin')->assertForbidden();
        $this->get(route('admin.sellers.create'))->assertForbidden();
    }

    public function test_admin_static_create_and_export_routes_resolve(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.sellers.create'))->assertOk();
        $this->get(route('admin.networks.create'))->assertOk();
        $this->get(route('admin.packages.create'))->assertOk();

        $export = $this->get(route('admin.inventory.export'));
        $export->assertOk();
        $this->assertStringContainsString('attachment', (string) $export->headers->get('content-disposition'));
    }

    public function test_admin_topup_reject_reason_max_length_validation(): void
    {
        app()->setLocale('ar');

        $customer = User::factory()->create(['role' => 'customer']);
        $wallet = Wallet::query()->create(['user_id' => $customer->id, 'status' => 'active']);
        $topup = TopupRequest::query()->create([
            'user_id' => $customer->id,
            'wallet_id' => $wallet->id,
            'method' => 'bank_transfer',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin);

        $response = $this->from(route('admin.topups.index'))->post(route('admin.topups.reject', $topup), [
            'reason' => str_repeat('a', 501),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('reason');
    }

    public function test_admin_can_refund_paid_wallet_order_and_restore_balances(): void
    {
        config(['marketplace.points.cash_equivalent_per_1000' => 1000]);

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

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
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
            'code' => 'CARD-REF',
            'price' => 1000,
            'status' => 'active',
        ]);

        $this->postJson('/api/v1/checkout/wallet', [
            'user_id' => $user->id,
            'idempotency_key' => 'refund-admin-1',
            'points_to_redeem' => 700,
            'items' => [
                ['package_id' => $package->id, 'quantity' => 1],
            ],
        ])->assertOk();

        $order = CardOrder::query()->first();
        $this->assertNotNull($order);
        $this->assertSame('paid', $order->status);

        $this->actingAs($this->admin);

        $this->post(route('admin.orders.refund', $order))->assertRedirect();

        $wallet = Wallet::query()->where('user_id', $user->id)->first();
        $this->assertSame(400.0, (float) $wallet->balance);
        $this->assertSame(700, (int) $wallet->points_balance);

        $this->assertDatabaseHas('cards', ['code' => 'CARD-REF', 'status' => 'active']);
        $this->assertDatabaseHas('card_orders', ['id' => $order->id, 'status' => 'cancelled']);
    }
}
