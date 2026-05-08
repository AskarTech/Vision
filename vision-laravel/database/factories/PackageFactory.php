<?php

namespace Database\Factories;

use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        return [
            'seller_id' => Seller::factory(),
            'network_id' => Network::factory(),
            'name' => fake()->randomElement(['باقة يومية', 'باقة أسبوعية', 'باقة شهرية']) . ' ' . fake()->numerify('##'),
            'price' => fake()->randomFloat(2, 200, 8000),
            'amount' => fake()->numberBetween(1, 120),
            'unit' => fake()->randomElement(['GB', 'Day', 'Hour']),
            'period_type' => fake()->randomElement(['daily', 'weekly', 'monthly']),
            'category' => fake()->randomElement(['best_selling', 'daily', 'weekly', 'monthly']),
            'gradient' => fake()->randomElement(['emerald', 'sky', 'violet']),
            'status' => fake()->randomElement(['active', 'active', 'disabled']),
            'meta' => ['featured' => fake()->boolean(30)],
        ];
    }
}
