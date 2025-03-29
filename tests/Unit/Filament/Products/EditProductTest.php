<?php

use App\Filament\Clusters\Products\Resources\ProductResource;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages\EditProduct;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Edit', function () {
    beforeEach(function () {
        $this->component = EditProduct::class;
        $this->product = Product::factory()->create();
        $this->route = ProductResource::getUrl('edit', ['record' => $this->product->id]);
        $this->user = User::factory()->create();
    });

    test('render the product edit page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Edit Product');
    });

    test('redirect to login page if not authenticated', function (){
    get($this->route)
    ->assertRedirect(route('filament.admin.auth.login'));
        });

    test('edit a product', function () {
        actingAs($this->user);

        $payload = Product::factory()->make()->toArray();

        $livewire = livewire($this->component, ['record' => $this->product->id]);
        $livewire->assertFormSet([]);
        $livewire->fillForm($payload)
            ->call('save')
            ->assertHasNoErrors();

        assertDatabaseHas('products', [
            'name' => $payload['name'],
        ]);

    });

    test('validation error for required fields', function () {
        actingAs($this->user);
        $livewire = livewire($this->component, ['record' => $this->product->id]);
        $livewire->assertFormSet($this->product->toArray());
        $livewire->fillForm([])
            ->call('save')
            ->assertHasFormErrors(['name']);
    });

});
