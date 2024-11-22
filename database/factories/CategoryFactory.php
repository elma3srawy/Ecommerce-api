<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Category::class;
    public function definition(): array
    {
        return [
            'parent_id' => null, // Set to `null` by default, can override for nested categories
            'created_by' => 1, // Creates an admin if not specified
            'updated_by' => null,
            'name' => $this->faker->unique()->word,
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'categories', true), // Random image URL
            'status' => $this->faker->boolean(80), // 80% chance of true
            'description' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
