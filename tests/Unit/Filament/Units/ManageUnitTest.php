<?php

use App\Filament\Clusters\StockManagement\Resources\UnitResource;
use App\Filament\Clusters\StockManagement\Resources\UnitResource\Pages\ManageUnits;
use App\Models\Unit;
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
        $this->component = ManageUnits::class;
        $this->route = UnitResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('redirect to login page if not authenticated', function () {
        Unit::factory(10)->create();
        get($this->route)
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    });

    // TODO: Implement Roles and Permissions
    // test('redirect to 403 Error Page page if unauthorized', function () {
    //     Unit::factory(10)->create();
    //     get($this->route)
    //         ->assertStatus(403)
    //         ->assertRedirectToRoute('login');
    // });

    test('render the units index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Units');
    });

    test('display table of units', function () {
        $data = Unit::factory(10)->create();

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
        $this->component = ManageUnits::class;
        $this->user = User::factory()->create();
    });

    test('show dialog to create a new unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountAction('create')
            ->assertSee('Create unit')
            ->assertSee('Name');
    });

    test('create a new unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Unit::factory()->make()->toArray();

        $livewire->mountAction('create')
            ->setActionData($payload)
            ->callAction('create');

        assertDatabaseHas('units', [
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
        $this->component = ManageUnits::class;
        $this->user = User::factory()->create();
        $this->unit = Unit::factory()->create();
    });

    test('show dialog to edit a new unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(EditAction::class, $this->unit)
            ->assertSee('Edit unit')
            ->assertSee('Name');
    });

    test('edit a unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Unit::factory()->make()->toArray();

        $livewire
            ->mountTableAction('edit', record: $this->unit)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->unit)
            ->assertHasNoTableActionErrors();

        assertDatabaseHas('units', [
            'name' => $payload['name'],
        ]);
    });

    test('validation error', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = ['name' => null];

        $livewire
            ->mountTableAction('edit', record: $this->unit)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->unit)
            ->assertHasTableActionErrors(['name' => ['required']]);
    });
});

describe('Delete', function () {
    beforeEach(function () {
        $this->component = ManageUnits::class;
        $this->user = User::factory()->create();
        $this->unit = Unit::factory()->create();
    });

    test('show dialog to delete a unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(DeleteAction::class, $this->unit)
            ->assertSee('Delete unit');
    });

    test('delete a unit', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire
            ->mountTableAction(DeleteAction::class, record: $this->unit)
            ->callTableAction(DeleteAction::class, $this->unit)
            ->assertHasNoTableActionErrors();

        assertSoftDeleted('units', [
            'name' => $this->unit->name,
        ]);
    });
});

describe('View', function () {
    beforeEach(function () {
        $this->component = ManageUnits::class;
        $this->user = User::factory()->create();
        $this->unit = Unit::factory()->create();
    });

    test('should view a unit', function () {
        actingAs($this->user);

        $livewire = livewire($this->component, [
            'record' => $this->unit->getRouteKey(),
        ]);

        $livewire->mountTableAction('view', record: $this->unit)
            ->callTableAction('view', record: $this->unit)
            ->assertSee([
                'name' => $this->unit->name,
            ]);
    });
});
