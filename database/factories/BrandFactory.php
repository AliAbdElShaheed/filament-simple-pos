<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandFactory extends Factory
{

    protected $model = Brand::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->unique()->name()),
            'url' => fake()->url(),
            'description' => fake()->text(),
            'logo' => null,
            'primary_hex_color' => fake()->hexColor(),
            'is_active' => fake()->boolean(),
            'is_visible' => fake()->boolean(),
            'notes' => null,
        ];
    }
}
