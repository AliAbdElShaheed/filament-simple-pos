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
            'unit_price' => fake()->randomFloat(2, 1, 100), // Random float between 1 and 100
            'total_units_price' => $this->quantity * $this->unit_price,
            'notes' => fake()->text(50),
        ];
    }
}
