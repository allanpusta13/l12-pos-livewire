<?php

use App\Filament\Clusters\Products\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('list', function () {
    beforeEach(function () {
        $this->component = ListProductsTest::class;
        $this->route = ProductResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('render the products index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertOk()
            ->assertSee('Products');
    });

    test('redirect to login page if not authenticated', function () { get($this->route)->assertStatus(403)->assertRedirect(route('filament.admin.auth.login')); });

    test('display table of products', function () {
        $products = Product::factory(10)->create();
        livewire($this->component)
            ->assertCanSeeTableRecords($products)
            ->assertCountTableRecords(10)
            ->assertTableColumnExists('name');

    });
});
