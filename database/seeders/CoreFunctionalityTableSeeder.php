<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Unit;
use App\Traits\SeederTrait;
use Illuminate\Database\Seeder;

class CoreFunctionalityTableSeeder extends Seeder
{
    use SeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generate units
        $units = [
            [
                'name' => 'Piece',
                'symbol' => 'pc',
                'description' => 'A single item or object.',
                'abbreviation' => 'pc',
            ],
            [
                'name' => 'Grams',
                'symbol' => 'g',
                'description' => 'A metric unit of mass equal to one thousandth of a kilogram.',
                'abbreviation' => 'g',
            ],
            [
                'name' => 'Kilograms',
                'symbol' => 'kg',
                'description' => 'A metric unit of mass equal to one thousand grams.',
                'abbreviation' => 'kg',
            ],
            [
                'name' => 'Liters',
                'symbol' => 'L',
                'description' => 'A metric unit of capacity, formerly defined as the volume of one kilogram of water under standard conditions, now equal to 1,000 cubic centimeters (about 1.75 pints).',
                'abbreviation' => 'L',
            ],
            [
                'name' => 'Milliliters',
                'symbol' => 'mL',
                'description' => 'A metric unit of volume equal to one thousandth of a liter.',
                'abbreviation' => 'mL',
            ],
        ];

        $this->command->warn(PHP_EOL.'Creating units...');
        $units = $this->withProgressBar(1, function () use ($units) {
            foreach ($units as $unit) {
                Unit::create($unit);
            }
        });
        $this->command->info('Units created.');

        $this->command->warn(PHP_EOL.'Creating products...');
        $products = $this->withProgressBar(1, function () {
            foreach (Unit::all() as $unit) {
                Product::factory(fake()->numberBetween(5, 20))->for($unit)->create();
            }
        });
        $this->command->info('Products created.');
    }
}
