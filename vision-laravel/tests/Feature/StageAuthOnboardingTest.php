<?php

namespace Tests\Feature;

use App\Actions\Auth\RegisterSellerOnboardingAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageAuthOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_redirects_to_marketplace(): void
    {
        $user = User::query()->create([
            'name' => 'عميل تجريبي',
            'phone' => '967770000030',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        $response = $this->post('/login', [
            'identifier' => $user->phone,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('customer.marketplace.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_customer_registration_redirects_to_marketplace(): void
    {
        $response = $this->post('/register', [
            'name' => 'زائر جديد',
            'phone' => '967770000031',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('customer.marketplace.index'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'phone' => '967770000031',
            'role' => 'customer',
        ]);
    }

    public function test_seller_onboarding_registers_entities_and_redirects_to_dashboard(): void
    {
        $payload = [
            'business_name' => 'شركة اتصالات تجريبية',
            'business_phone' => '967712345670',
            'manager_name' => 'مدير الحساب',
            'manager_phone' => '967770000032',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'network_name' => 'شبكة عدن الأولى',
            'network_provider_code' => 'ADN-001',
            'wallet_display_label' => 'محفظة المبيعات المحلية',
        ];

        $response = $this->post('/register/seller', $payload);

        $response->assertRedirect(route('seller.dashboard'));

        $this->assertDatabaseHas('sellers', ['name' => 'شركة اتصالات تجريبية']);
        $this->assertDatabaseHas('networks', ['name' => 'شبكة عدن الأولى']);
        $user = User::query()->where('phone', '967770000032')->first();
        $this->assertNotNull($user);
        $this->assertSame('seller_manager', $user->role);

        $this->assertDatabaseHas('users', [
            'phone' => '967770000032',
            'role' => 'seller_manager',
        ]);
        $this->assertDatabaseHas('seller_managers', [
            'user_id' => $user->id,
            'seller_id' => $user->seller_id,
        ]);
        $this->assertDatabaseHas('wallets', ['user_id' => $user->id]);
    }

    public function test_seller_wallet_and_sales_pages_respond_ok(): void
    {
        /** @var RegisterSellerOnboardingAction $registerSeller */
        $registerSeller = app(RegisterSellerOnboardingAction::class);

        $user = $registerSeller->execute([
            'business_name' => 'متجر تجريبي للمسارات',
            'business_phone' => '967712345671',
            'manager_name' => 'مسؤول البيع',
            'manager_phone' => '967770000033',
            'password' => 'secret123',
            'network_name' => 'شبكة تجريبية',
            'network_provider_code' => null,
            'wallet_display_label' => null,
        ]);

        $this->actingAs($user);

        $this->get(route('seller.wallet.index'))->assertOk();
        $this->get(route('seller.sales.index'))->assertOk();
    }
}
