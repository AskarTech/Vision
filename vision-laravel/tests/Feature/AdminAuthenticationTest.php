<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_opening_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_access_admin_dashboard(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin User',
            'phone' => '967770000010',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        $loginResponse = $this->post('/login', [
            'identifier' => $admin->phone,
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $loginResponse->assertRedirect('/admin');

        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->get('/admin');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('لوحة الإدارة');
        $dashboardResponse->assertSee('مركز تشغيل لمراجعة الطلبات');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::query()->create([
            'name' => 'Customer User',
            'phone' => '967770000011',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'secret123',
        ]);

        $this->actingAs($customer);

        $response = $this->get('/admin');

        $response->assertForbidden();
    }
}
