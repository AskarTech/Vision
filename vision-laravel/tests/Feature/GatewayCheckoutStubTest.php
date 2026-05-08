<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GatewayCheckoutStubTest extends TestCase
{
    use RefreshDatabase;

    public function test_gateway_init_returns_stub_payload(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $package = Package::factory()->create(['status' => 'active']);

        $response = $this->postJson('/api/v1/checkout/gateway/init', [
            'user_id' => $user->id,
            'gateway' => 'floosak',
            'items' => [
                ['package_id' => $package->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(503)->assertJsonFragment(['code' => 'gateway_stub']);
    }
}
