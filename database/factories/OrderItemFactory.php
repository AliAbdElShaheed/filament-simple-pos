<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemFactory extends Factory
{

    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->randomNumber(),
            'unit_price' => fake()->word(),
            'total_units_price' => fake()->word(),
            'notes' => fake()->text(),
        ];
    }
}
