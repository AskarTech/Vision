<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(10, 9999)),
            'phone' => '+9677' . fake()->numerify('#######'),
            'commission_rate' => fake()->randomFloat(2, 3, 15),
            'status' => fake()->randomElement(['active', 'active', 'disabled']),
            'settings' => [
                'theme' => fake()->randomElement(['default', 'dark']),
                'notifications' => fake()->boolean(80),
            ],
        ];
    }
}
