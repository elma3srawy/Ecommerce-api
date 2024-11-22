<?php

namespace Database\Factories;

use App\Models\ProductPricingHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPricingHistoryFactory extends Factory
{
    protected $model = ProductPricingHistory::class;

    public function definition(): array
    {
        return [
            'old_price' => $this->faker->randomFloat(2, 1, 1000),
            'new_price' => $this->faker->randomFloat(2, 1, 1000),
            'changeable_id' => 1,
            'changeable_type' => 'App\Models\Admin', 
            'changed_at' => $this->faker->dateTime(),
        ];
    }
}
