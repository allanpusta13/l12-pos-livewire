<?php

use App\Filament\Resources\LocationResource;
use App\Filament\Resources\LocationResource\Pages\EditLocation;
use App\Models\Location;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Edit', function () {
    beforeEach(function () {
        $this->component = EditLocation::class;
        $this->location = Location::factory()->create();
        $this->route = LocationResource::getUrl('edit', ['record' => $this->location->id]);
        $this->user = User::factory()->create();
    });

    test('render the location edit page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Edit Location');
    });

    test('redirect to login page if not authenticated', function (){
    get($this->route)
    ->assertRedirect(route('filament.admin.auth.login'));
        });

    test('edit a location', function () {
        actingAs($this->user);

        $payload = Location::factory()->make()->toArray();

        $livewire = livewire($this->component, ['record' => $this->location->id]);
        $livewire->assertFormSet([]);
        $livewire->fillForm($payload)
            ->call('save')
            ->assertHasNoErrors();

        assertDatabaseHas('locations', [
            'name' => $payload['name'],
        ]);

    });

    test('validation error for required fields', function () {
        actingAs($this->user);
        $livewire = livewire($this->component, ['record' => $this->location->id]);
        $livewire->assertFormSet($this->location->toArray());
        $livewire->fillForm([])
            ->call('save')
            ->assertHasFormErrors(['name']);
    });

});
