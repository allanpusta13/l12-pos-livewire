<?php

use App\Filament\Resources\LocationResource;
use App\Filament\Resources\LocationResource\Pages\ViewLocation;
use App\Models\Location;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('View', function () {
    beforeEach(function () {
        $this->component = ViewLocation::class;
        $this->location = Location::factory()->create();
        $this->route = LocationResource::getUrl('view', ['record' => $this->location->id]);
        $this->user = User::factory()->create();
    });

    test('render the location view page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('View Location');
    });

    test('redirect to login page if not authenticated', function (){
        get($this->route)
            ->assertRedirect(route('login'));
    });

    test('view a location', function () {
        actingAs($this->user);
        $livewire = livewire($this->component, ['record' => $this->location->id]);
        $livewire->assertFormSet($this->location->toArray());
    });

    test('delete location from view page', function () {
        actingAs($this->user);
        $livewire = livewire($this->component, ['record' => $this->location->id]);
        $livewire
            ->callAction('delete', $this->location->toArray())
            ->assertHasNoActionErrors();

        assertSoftDeleted($this->location);
    });
});
