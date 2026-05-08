<?php

namespace Database\Factories;

use App\Models\Network;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Network>
 */
class NetworkFactory extends Factory
{
    protected $model = Network::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['Yemen Mobile', 'Sabafon', 'You', 'MTN']) . ' ' . fake()->numerify('##');

        return [
            'seller_id' => Seller::factory(),
            'name' => $name,
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(1, 999)),
            'provider_code' => strtoupper(fake()->bothify('NW-###??')),
            'status' => fake()->randomElement(['active', 'active', 'disabled']),
            'meta' => [
                'region' => fake()->city(),
            ],
        ];
    }
}
