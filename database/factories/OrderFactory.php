<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Order;

class OrderFactory extends Factory
{

    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_number' => fake()->word(),
            'customer_id' => $this->faker->randomElement(Customer::all()->pluck('id')->toArray()),
            'total_amount' => fake()->word(),
            'status' => fake()->word(),
            'payment_status' => fake()->word(),
            'shipping_price' => fake()->word(),
            'shipping_address' => fake()->word(),
            'billing_address' => fake()->word(),
            'placed_at' => fake()->dateTime(),
            'delivered_at' => fake()->dateTime(),
            'cancelled_at' => fake()->dateTime(),
            'notes' => fake()->text(),
        ];
    }
}
