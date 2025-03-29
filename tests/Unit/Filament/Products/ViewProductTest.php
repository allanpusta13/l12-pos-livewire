<?php

use App\Filament\Clusters\Products\Resources\ProductResource;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages\ViewProduct;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('View', function () {
    beforeEach(function () {
        $this->component = ViewProduct::class;
        $this->product = Product::factory()->create();
        $this->route = ProductResource::getUrl('view', ['record' => $this->product->id]);
        $this->user = User::factory()->create();
    });

    test('render the product view page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('View Product');
    });

    test('redirect to login page if not authenticated', function (){
    get($this->route)
    ->assertRedirect(route('filament.admin.auth.login'));
        });

    test('view a product', function () {
        actingAs($this->user);
        $livewire = livewire($this->component, ['record' => $this->product->id]);
        $livewire->assertFormSet($this->product->toArray());
    });
});
