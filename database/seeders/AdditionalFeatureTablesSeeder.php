<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Traits\SeederTrait;
use Illuminate\Database\Seeder;

class AdditionalFeatureTablesSeeder extends Seeder
{
    use SeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL.'Creating locations...');
        $this->withProgressBar(1, function () {
            Location::factory(5)->create();
        });
        $this->command->info('Locations created.');
    }
}
