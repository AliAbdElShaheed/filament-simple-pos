<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryFactory extends Factory
{

    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->unique()->name()),
            'parent_id' => null,
            'description' => fake()->text(),
            'is_active' => fake()->boolean(),
            'is_visible' => fake()->boolean(),
        ];
    }
}
