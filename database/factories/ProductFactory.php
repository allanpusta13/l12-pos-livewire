<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    private static $skuCounter = null;

    private static $has_composition = false;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        self::$has_composition = fake()->boolean();
        $manage_stock = self::$has_composition ? false : fake()->boolean();

        return [
            'name' => fake()->word,
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(1, 200),
            'unit_id' => Unit::factory(),
            'is_active' => fake()->boolean(),
            'is_public' => fake()->boolean(),
            'sku' => $this->generateSku(),
            'barcode' => fake()->ean13(),
            'has_composition' => self::$has_composition,
            'manage_stock' => $manage_stock,
            'cost' => fake()->numberBetween(1, 200),
        ];
    }

    private function generateSku()
    {
        if (self::$skuCounter === null) {
            self::$skuCounter = Product::max('sku') ?? 99999; // Start from 1000
        }

        return ++self::$skuCounter;
    }
}
