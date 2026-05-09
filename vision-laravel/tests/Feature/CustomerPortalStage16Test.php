<?php

namespace Tests\Feature;

use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalStage16Test extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: Seller, 1: Network, 2: Package}
     */
    private function seedActivePackageWithoutCards(): array
    {
        $seller = Seller::query()->create([
            'name' => 'Stage16 Seller',
            'slug' => 'stage16-seller',
            'status' => 'active',
        ]);

        $network = Network::query()->create([
            'seller_id' => $seller->id,
            'name' => 'Stage16 Net',
            'slug' => 'stage16-net',
            'status' => 'active',
        ]);

        $package = Package::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'name' => 'Empty Stock Pack',
            'price' => 100,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ]);

        return [$seller, $network, $package];
    }

    private function actingCustomerWithWallet(float $balance = 5000): User
    {
        $user = User::query()->create([
            'name' => 'Stage16 Customer',
            'phone' => '967770000055',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        Wallet::forceCreate([
            'user_id' => $user->id,
            'balance' => $balance,
            'points_balance' => 120,
            'status' => 'active',
        ]);

        return $user;
    }

    public function test_web_buy_blocked_when_package_has_no_active_cards(): void
    {
        [, , $package] = $this->seedActivePackageWithoutCards();

        $user = $this->actingCustomerWithWallet();

        $this->actingAs($user);

        $response = $this->post(route('customer.marketplace.buy', $package), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('card_orders', 0);
    }

    public function test_marketplace_package_detail_shows_out_of_stock_state(): void
    {
        [, , $package] = $this->seedActivePackageWithoutCards();

        $user = $this->actingCustomerWithWallet();

        $this->actingAs($user);

        $this->get(route('customer.marketplace.show', $package))
            ->assertOk()
            ->assertSee('لا يمكن الشراء', false)
            ->assertSee('غير متوفر', false);
    }

    public function test_customer_dashboard_includes_points_metric(): void
    {
        $this->seedActivePackageWithoutCards();

        $user = $this->actingCustomerWithWallet();

        $this->actingAs($user);

        $this->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('النقاط', false)
            ->assertSee('120', false);
    }

    public function test_customer_navigation_gates_allow_marketplace_for_customer_role(): void
    {
        $user = $this->actingCustomerWithWallet();

        $this->actingAs($user);

        $this->assertTrue($user->can('view_dashboard'));
        $this->assertTrue($user->can('purchase_cards'));
        $this->assertTrue($user->can('view_orders'));
        $this->assertTrue($user->can('view_wallet'));
    }
}
