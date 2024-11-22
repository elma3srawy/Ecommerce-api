<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => rand(1 , 10),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true),
        ];
    }
}
