<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerAccountLinkageTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_manager_without_seller_id_sees_helpful_page_not_opaque_forbidden(): void
    {
        $user = User::query()->create([
            'name' => 'Orphan Manager',
            'phone' => '967770000099',
            'role' => 'seller_manager',
            'seller_id' => null,
            'status' => 'active',
            'password' => 'secret123',
        ]);

        $this->actingAs($user);

        $response = $this->get('/seller');

        $response->assertStatus(403);
        $response->assertSee('تعذّر فتح لوحة الشريك', false);
        $response->assertSee('seller_id', false);
    }
}
