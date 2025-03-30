<?php

namespace Database\Factories;

use App\Enums\LocationTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'type' => fake()->randomElement(collect(LocationTypeEnum::cases())->pluck('value')),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'is_active' => fake()->boolean(),
        ];
    }
}
