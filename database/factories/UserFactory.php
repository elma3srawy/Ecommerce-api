<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email has been verified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function verified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model has a phone number verification code.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPhoneVerification()
    {
        return $this->state(function (array $attributes) {
            return [
                'mobile_verified_at' => now(),
            ];
        });
    }
}
