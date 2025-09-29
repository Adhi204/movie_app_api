<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favourite>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'title' => fake()->title(),
            'year' => fake()->year(),
            'like_count' => fake()->numberBetween(1, 100),
        ];
    }
}
