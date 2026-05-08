<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Card>
 */
class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        $package = Package::query()->inRandomOrder()->first() ?? Package::factory()->create();

        return [
            'seller_id' => $package->seller_id,
            'network_id' => $package->network_id,
            'package_id' => $package->id,
            'code' => strtoupper(Str::random(12)),
            'serial_number' => strtoupper(fake()->bothify('SN-######')),
            'price' => fake()->randomFloat(2, 200, 8000),
            'status' => fake()->randomElement(['active', 'active', 'reserved', 'sold', 'disabled']),
            'uploaded_at' => now()->subDays(fake()->numberBetween(1, 90)),
            'meta' => ['batch' => fake()->numerify('B-###')],
        ];
    }
}
