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
        $purchasePrice = fake()->randomFloat(2, 0, 100);
        $salePrice = fake()->randomFloat(2, 100, 200);
        $productProfit = $salePrice - $purchasePrice;

        return [
            'name' => $name = fake()->unique()->word(),
            'slug' => Str::slug($name),
            'brand_id' => Brand::factory(),
            'sku' => fake()->unique()->word(),
            'bar_code' => fake()->ean13(),
            'description' => fake()->text(),
            'image' => null,
            'type' => $this->faker->randomElement(ProductType::class),
            'purchase_price' => $purchasePrice,
            'sale_price' => $salePrice,
            'product_profit' => $productProfit,

            'quantity' => fake()->numberBetween(10, 100),
            'minimum_quantity' => fake()->numberBetween(0, 10),

            'is_active' => fake()->boolean(),
            'is_visible' => fake()->boolean(),
            'is_featured' => fake()->boolean(),
            'published_at' => fake()->dateTime(),
        ];
    }
}
