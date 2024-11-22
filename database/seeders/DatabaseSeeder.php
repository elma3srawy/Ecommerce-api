<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductPricingHistory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123123123')
        ]);

        User::factory(50)->withPhoneVerification()->create();

        Category::factory(10)->create();

        Product::factory(500)
        ->has(ProductAttribute::factory(3), 'attributes')
        ->has(ProductPricingHistory::factory(3), 'pricingHistory')
        ->create();

        Coupon::factory(50)->create();

    }
}
