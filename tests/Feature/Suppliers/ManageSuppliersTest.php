<?php

use App\Filament\Clusters\Contacts\Resources\SupplierResource;
use App\Filament\Clusters\Contacts\Resources\SupplierResource\Pages\ManageSuppliers;
use App\Models\Supplier;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('List', function () {
    beforeEach(function () {
        $this->component = ManageSuppliers::class;
        $this->route = SupplierResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('redirect to login page if not authenticated', function () {
        Supplier::factory(10)->create();
        get($this->route)
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    });

    // TODO: Implement Roles and Permissions
    // test('redirect to 403 Error Page page if unauthorized', function () {
    //     Supplier::factory(10)->create();
    //     get($this->route)
    //         ->assertStatus(403)
    //         ->assertRedirectToRoute('login');
    // });

    test('render the suppliers index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Suppliers');
    });

    test('display table of suppliers', function () {
        $data = Supplier::factory(10)->create();

        livewire($this->component)
            ->assertCanSeeTableRecords($data)
            ->assertCountTableRecords(10)
            ->assertTableColumnExists('name')
            ->assertTableActionExists(ViewAction::class)
            ->assertTableActionExists(EditAction::class)
            ->assertTableActionExists(DeleteAction::class)
            ->assertTableActionExists(RestoreAction::class)
            ->assertTableActionExists(ForceDeleteAction::class);
    });
});

describe('Create', function () {
    beforeEach(function () {
        $this->component = ManageSuppliers::class;
        $this->user = User::factory()->create();
    });

    test('show dialog to create a new supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountAction('create')
            ->assertSee('Create supplier')
            ->assertSee('Name');
    });

    test('create a new supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Supplier::factory()->make()->toArray();

        $livewire->mountAction('create')
            ->setActionData($payload)
            ->callAction('create');

        assertDatabaseHas('contacts', [
            'name' => $payload['name'],
        ]);
    });

    test('validation error', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = [
            'name' => null,
        ];

        $livewire->mountAction('create')
            ->setActionData($payload)
            ->callAction('create')
            ->assertHasActionErrors(['name' => ['required']]);
    });
});

describe('Edit', function () {
    beforeEach(function () {
        $this->component = ManageSuppliers::class;
        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
    });

    test('show dialog to edit a new supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(EditAction::class, $this->supplier)
            ->assertSee('Edit supplier')
            ->assertSee('Name');
    });

    test('edit a supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Supplier::factory()->make()->toArray();

        $livewire
            ->mountTableAction('edit', record: $this->supplier)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->supplier)
            ->assertHasNoTableActionErrors();

        assertDatabaseHas('contacts', [
            'name' => $payload['name'],
        ]);
    });

    test('validation error', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = ['name' => null];

        $livewire
            ->mountTableAction('edit', record: $this->supplier)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->supplier)
            ->assertHasTableActionErrors(['name' => ['required']]);
    });
});

describe('Delete', function () {
    beforeEach(function () {
        $this->component = ManageSuppliers::class;
        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
    });

    test('show dialog to delete a supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(DeleteAction::class, $this->supplier)
            ->assertSee('Delete supplier');
    });

    test('delete a supplier', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire
            ->mountTableAction(DeleteAction::class, record: $this->supplier)
            ->callTableAction(DeleteAction::class, $this->supplier)
            ->assertHasNoTableActionErrors();

        assertSoftDeleted('contacts', [
            'name' => $this->supplier->name,
        ]);
    });
});

describe('View', function () {
    beforeEach(function () {
        $this->component = ManageSuppliers::class;
        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
    });

    test('should view a supplier', function () {
        actingAs($this->user);

        $livewire = livewire($this->component, [
            'record' => $this->supplier->getRouteKey(),
        ]);

        $livewire->mountTableAction('view', record: $this->supplier)
            ->callTableAction('view', record: $this->supplier)
            ->assertSee([
                'name' => $this->supplier->name,
            ]);
    });
});
