<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;

class CustomerFactory extends Factory
{

    protected $model = Customer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'phone2' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'address2' => fake()->address(),
            'date_of_birth' => fake()->dateTimeBetween('-40 years', '-20 years')->format('Y-m-d'),
            'country' => fake()->country(),
            'state' => fake()->citySuffix(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'additional_info' => fake()->text(),
        ];
    }
}
