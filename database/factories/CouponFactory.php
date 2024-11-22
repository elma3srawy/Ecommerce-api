<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => strtoupper(Str::random(10)),  // Random 10 character code
            'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'value' => $this->faker->randomFloat(2, 1, 100), // Random value between 1 and 100
            'minimum_order_value' => $this->faker->randomFloat(2, 0, 50), // Random minimum order value
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'), // Random date within the next month
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+3 months'), // Random date within the next 3 months
            'usage_limit' => $this->faker->numberBetween(1, 100), // Random usage limit
            'usage_count' => $this->faker->numberBetween(0, 10), // Random usage count
            'is_active' => $this->faker->boolean, // Random boolean for active status
        ];
    }

}
