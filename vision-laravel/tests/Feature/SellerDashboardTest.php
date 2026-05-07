<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_opening_seller_dashboard(): void
    {
        $response = $this->get('/seller');

        $response->assertRedirect('/login');
    }

    public function test_seller_manager_can_login_and_access_seller_dashboard(): void
    {
        $seller = \App\Models\Seller::query()->create([
            'name' => 'Vision Seller',
            'slug' => 'vision-seller',
            'status' => 'active',
        ]);

        $user = User::query()->create([
            'name' => 'Seller Manager',
            'phone' => '967770000020',
            'role' => 'seller_manager',
            'seller_id' => $seller->id,
            'status' => 'active',
            'password' => 'secret123',
        ]);

        \App\Models\SellerManager::query()->create([
            'seller_id' => $seller->id,
            'user_id' => $user->id,
            'username' => 'seller.manager',
            'status' => 'active',
        ]);

        Network::query()->create([
            'seller_id' => $seller->id,
            'name' => 'Yemen Net',
            'slug' => 'yemen-net',
            'status' => 'active',
        ]);

        $network = Network::query()->firstOrFail();

        $package = Package::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'name' => '10GB Monthly',
            'price' => 1000,
            'period_type' => 'monthly',
            'category' => 'monthly',
            'status' => 'active',
        ]);

        Card::query()->create([
            'seller_id' => $seller->id,
            'network_id' => $network->id,
            'package_id' => $package->id,
            'code' => 'CARD-S-001',
            'price' => 1000,
            'status' => 'active',
        ]);

        $loginResponse = $this->post('/login', [
            'identifier' => $user->phone,
            'password' => 'secret123',
            'role' => 'seller_manager',
        ]);

        $loginResponse->assertRedirect('/seller');

        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->get('/seller');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('لوحة البائع');
        $dashboardResponse->assertSee('Vision Seller');
        $dashboardResponse->assertSee('الكروت النشطة');
    }

    public function test_admin_cannot_access_seller_dashboard(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin User',
            'phone' => '967770000021',
            'role' => 'admin',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        $this->actingAs($admin);

        $response = $this->get('/seller');

        $response->assertForbidden();
    }
}
