<?php

namespace Database\Factories;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'attribute_name' => $this->faker->randomElement(['Color', 'Size', 'Material']),
            'attribute_value' => $this->faker->randomElement(['Red', 'Large', 'Cotton']),
        ];
    }
}
