<?php

use App\Filament\Resources\LocationResource;
use App\Filament\Resources\LocationResource\Pages\ListLocations;
use App\Models\Location;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('list', function () {
    beforeEach(function () {
        $this->component = ListLocations::class;
        $this->route = LocationResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('render the locations index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertOk()
            ->assertSee('Locations');
    });

    test('redirect to login page if not authenticated', function () { get($this->route)->assertRedirect(route('filament.admin.auth.login')); });

    test('display table of locations', function () {
        $locations = Location::factory(10)->create();
        livewire($this->component)
            ->assertCanSeeTableRecords($locations)
            ->assertCountTableRecords(10)
            ->assertTableColumnExists('name');

    });
});
