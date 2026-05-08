<?php

namespace Tests\Feature;

use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerPortalScopeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: Seller, 1: User}
     */
    private function seedSellerWithManager(): array
    {
        $entropy = bin2hex(random_bytes(10));

        $seller = Seller::query()->create([
            'name' => 'Seller '.$entropy,
            'slug' => 'seller-'.$entropy,
            'phone' => '+967'.substr($entropy, 0, 12),
            'status' => 'active',
            'commission_rate' => 5,
        ]);

        $user = User::query()->create([
            'name' => 'Manager '.$entropy,
            'phone' => '967'.substr($entropy, 12, 8).substr($entropy, 0, 8),
            'role' => 'seller_manager',
            'seller_id' => $seller->id,
            'status' => 'active',
            'password' => 'secret123',
        ]);

        SellerManager::query()->create([
            'seller_id' => $seller->id,
            'user_id' => $user->id,
            'username' => 'mgr-'.$entropy,
            'status' => 'active',
        ]);

        return [$seller, $user];
    }

    public function test_seller_can_create_network_and_package(): void
    {
        [$seller, $user] = $this->seedSellerWithManager();

        $this->actingAs($user);

        $this->get(route('seller.networks.create'))->assertOk();

        $this->post(route('seller.networks.store'), [
            'name' => 'Net One',
            'status' => 'active',
        ])->assertRedirect(route('seller.networks.index'));

        $network = Network::query()->where('seller_id', $seller->id)->firstOrFail();

        $this->get(route('seller.packages.create'))->assertOk();

        $this->post(route('seller.packages.store'), [
            'network_id' => $network->id,
            'name' => 'Pkg One',
            'price' => 500,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ])->assertRedirect(route('seller.packages.index'));

        $this->assertDatabaseHas('packages', [
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'name' => 'Pkg One',
        ]);
    }

    public function test_packages_create_redirects_when_no_active_network(): void
    {
        [$seller, $user] = $this->seedSellerWithManager();

        Network::query()->create([
            'seller_id' => $seller->id,
            'name' => 'Disabled Net',
            'slug' => 'disabled-'.$seller->id,
            'status' => 'disabled',
        ]);

        $this->actingAs($user);

        $this->get(route('seller.packages.create'))
            ->assertRedirect(route('seller.networks.create'))
            ->assertSessionHas('error');
    }

    public function test_seller_cannot_edit_other_sellers_network(): void
    {
        [, $userA] = $this->seedSellerWithManager();
        [$sellerB] = $this->seedSellerWithManager();

        $foreignNetwork = Network::query()->create([
            'seller_id' => $sellerB->id,
            'name' => 'Foreign',
            'slug' => 'foreign-'.$sellerB->id,
            'status' => 'active',
        ]);

        $this->actingAs($userA);

        $this->get(route('seller.networks.edit', $foreignNetwork))->assertForbidden();
    }

    public function test_seller_cannot_edit_other_sellers_package(): void
    {
        [, $userA] = $this->seedSellerWithManager();
        [$sellerB] = $this->seedSellerWithManager();

        $networkB = Network::query()->create([
            'seller_id' => $sellerB->id,
            'name' => 'B Net',
            'slug' => 'b-net-'.$sellerB->id,
            'status' => 'active',
        ]);

        $packageB = Package::query()->create([
            'seller_id' => $sellerB->id,
            'network_id' => $networkB->id,
            'name' => 'B Pkg',
            'price' => 100,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ]);

        $this->actingAs($userA);

        $this->get(route('seller.packages.edit', $packageB))->assertForbidden();
    }

    public function test_seller_sidebar_permission_gates_are_not_false_positives(): void
    {
        [, $user] = $this->seedSellerWithManager();

        $this->actingAs($user);

        $this->assertTrue($user->can('view_dashboard'), 'Seller overview link requires view_dashboard gate');
        $this->assertTrue($user->can('view_orders'), 'Seller orders link requires view_orders gate');
        $this->assertTrue($user->can('manage_networks'));
        $this->assertTrue($user->can('manage_packages'));
        $this->assertTrue($user->can('manage_inventory'));
        $this->assertTrue($user->can('view_sales'));
        $this->assertTrue($user->can('request_withdrawal'));
        $this->assertTrue($user->can('view_wallet'));
    }

    public function test_seller_can_update_profile_and_business_settings(): void
    {
        [$seller, $user] = $this->seedSellerWithManager();

        $this->actingAs($user);

        $this->patch(route('seller.settings.profile'), [
            'name' => 'Updated Manager',
            'phone' => '967779991122',
            'email' => null,
        ])->assertRedirect(route('seller.settings.index'));

        $user->refresh();
        $this->assertSame('Updated Manager', $user->name);
        $this->assertSame('967779991122', $user->phone);

        $this->patch(route('seller.settings.business'), [
            'business_name' => 'Updated Biz LLC',
            'business_phone' => '967778887766',
        ])->assertRedirect(route('seller.settings.index'));

        $seller->refresh();
        $this->assertSame('Updated Biz LLC', $seller->name);
        $this->assertSame('967778887766', $seller->phone);
    }
}
