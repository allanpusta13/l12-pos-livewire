<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(1, 200),
            'unit_id' => Unit::factory(),
            'is_ingredient' => fake()->boolean(),
            'is_active' => fake()->boolean(),
        ];
    }
}
