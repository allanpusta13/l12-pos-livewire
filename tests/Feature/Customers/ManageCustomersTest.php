<?php

use App\Filament\Clusters\Contacts\Resources\CustomerResource;
use App\Filament\Clusters\Contacts\Resources\CustomerResource\Pages\ManageCustomers;
use App\Models\Customer;
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
        $this->component = ManageCustomers::class;
        $this->route = CustomerResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('redirect to login page if not authenticated', function () {
        Customer::factory(10)->create();
        get($this->route)
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    });

    // TODO: Implement Roles and Permissions
    // test('redirect to 403 Error Page page if unauthorized', function () {
    //     Customer::factory(10)->create();
    //     get($this->route)
    //         ->assertStatus(403)
    //         ->assertRedirectToRoute('login');
    // });

    test('render the customers index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Customers');
    });

    test('display table of customers', function () {
        $data = Customer::factory(10)->create();

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
        $this->component = ManageCustomers::class;
        $this->user = User::factory()->create();
    });

    test('show dialog to create a new customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountAction('create')
            ->assertSee('Create customer')
            ->assertSee('Name');
    });

    test('create a new customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Customer::factory()->make()->toArray();

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
        $this->component = ManageCustomers::class;
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
    });

    test('show dialog to edit a new customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(EditAction::class, $this->customer)
            ->assertSee('Edit customer')
            ->assertSee('Name');
    });

    test('edit a customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Customer::factory()->make()->toArray();

        $livewire
            ->mountTableAction('edit', record: $this->customer)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->customer)
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
            ->mountTableAction('edit', record: $this->customer)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->customer)
            ->assertHasTableActionErrors(['name' => ['required']]);
    });
});

describe('Delete', function () {
    beforeEach(function () {
        $this->component = ManageCustomers::class;
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
    });

    test('show dialog to delete a customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(DeleteAction::class, $this->customer)
            ->assertSee('Delete customer');
    });

    test('delete a customer', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire
            ->mountTableAction(DeleteAction::class, record: $this->customer)
            ->callTableAction(DeleteAction::class, $this->customer)
            ->assertHasNoTableActionErrors();

        assertSoftDeleted('contacts', [
            'name' => $this->customer->name,
        ]);
    });
});

describe('View', function () {
    beforeEach(function () {
        $this->component = ManageCustomers::class;
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
    });

    test('should view a customer', function () {
        actingAs($this->user);

        $livewire = livewire($this->component, [
            'record' => $this->customer->getRouteKey(),
        ]);

        $livewire->mountTableAction('view', record: $this->customer)
            ->callTableAction('view', record: $this->customer)
            ->assertSee([
                'name' => $this->customer->name,
            ]);
    });
});
