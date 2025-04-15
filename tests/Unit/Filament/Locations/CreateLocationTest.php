<?php

use App\Filament\Resources\LocationResource;
use App\Filament\Resources\LocationResource\Pages\CreateLocation;
use App\Models\Location;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Create', function () {
    beforeEach(function () {
        $this->component = CreateLocation::class;
        $this->route = LocationResource::getUrl('create');
        $this->user = User::factory()->create();
    });

    test('render the location create page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Create Location');
    });

    test('redirect to login page if not authenticated', function () {
        //
        get($this->route)
            ->assertRedirect(route('login'));
    });

    test('create location', function () {
        actingAs($this->user);

        $livewire = livewire($this->component)
            ->assertSee('Create Location');

        $formSet = [];
        $fillSet = Location::factory()->make()->toArray();

        $livewire->assertFormSet($formSet);

        $livewire->fillForm($fillSet)
            ->call('create')
            ->assertHasNoErrors();

        assertDatabaseHas('locations', [
            'name' => $fillSet['name'],
        ]);
    });

    test('required validation error', function () {
        actingAs($this->user);

        $livewire = livewire($this->component);

        $formSet = [];

        $livewire->assertFormSet($formSet);

        $livewire->fillForm($formSet)
            ->call('create')
            ->assertHasFormErrors(['name']);

    });
});
