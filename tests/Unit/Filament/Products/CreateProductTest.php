<?php

use App\Filament\Clusters\Products\Resources\ProductResource;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages\CreateProduct;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Create', function () {
    beforeEach(function () {
        $this->component = CreateProduct::class;
        $this->route = ProductResource::getUrl('create');
        $this->user = User::factory()->create();
    });

    test('render the product create page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Create Product');
    });

    test('redirect to login page if not authenticated', function () {
    get($this->route)
    ->assertRedirect(route('filament.admin.auth.login'));
        });

    test('create product', function () {
        actingAs($this->user);

        $livewire = livewire($this->component)
            ->assertSee('Create Product');

        $formSet = [];
        $fillSet = Product::factory()->make()->toArray();

        $livewire->assertFormSet($formSet);

        $livewire->fillForm($fillSet)
            ->call('create')
            ->assertHasNoErrors();

        assertDatabaseHas('products', [
            'name' => $fillSet['name'],
            'description' => $fillSet['description'],
            'price' => $fillSet['price'],
            'unit_id' => $fillSet['unit_id'],
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
