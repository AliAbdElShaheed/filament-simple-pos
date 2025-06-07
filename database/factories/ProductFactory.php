<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{

    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->unique()->name()),
            'brand_id' => Brand::factory(),
            'sku' => fake()->unique()->word(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 1, 1000), // Random price between 1 and 1000
            'quantity' => fake()->numberBetween(1, 100),
            'image' => null,
            'type' => $this->faker->randomElement(ProductType::class),
            'is_active' => fake()->boolean(),
            'is_visible' => fake()->boolean(),
            'is_featured' => fake()->boolean(),
            'published_at' => fake()->dateTime(),
        ];
    }
}
