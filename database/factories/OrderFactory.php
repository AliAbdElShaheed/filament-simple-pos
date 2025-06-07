<?php

namespace Database\Factories;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
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
            'order_number' => fake()->unique()->numerify('ORD-#####'), // Unique order number
            'customer_id' => $this->faker->randomElement(Customer::all()->pluck('id')->toArray()),
            'total_amount' => fake()->randomFloat(2, 10, 1000), // Random float between 10 and 1000
            'status' => $this->faker->randomElement(OrderStatus::class),
            'payment_status' => $this->faker->randomElement(OrderPaymentStatus::class),
            'shipping_price' => fake()->randomFloat(2, 0, 100), // Random float between 0 and 100
            'shipping_address' => fake()->word(),
            'billing_address' => fake()->word(),
            'placed_at' => fake()->dateTime()->format('Y-m-d H:i'),
            'delivered_at' => fake()->dateTime()->format('Y-m-d H:i'),
            'cancelled_at' => fake()->dateTime(),
            'notes' => fake()->text(),
        ];
    }
}
