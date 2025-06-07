<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Ali Mohammed',
            'email' => 'admin@app.com',
            'is_admin' => true,
            'password' => bcrypt('password'),
        ]);

        Category::factory(10)->create();
        Product::factory(10)->create();
        Customer::factory(10)->create();
    }
}
