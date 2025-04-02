<?php

use App\Filament\Clusters\StockManagement\Resources\BatchResource;
use App\Filament\Clusters\StockManagement\Resources\BatchResource\Pages\ManageBatches;
use App\Models\Batch;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('List', function () {
    beforeEach(function () {
        $this->component = ManageBatches::class;
        $this->route = BatchResource::getUrl('index');
        $this->user = User::factory()->create();
    });

    test('redirect to login page if not authenticated', function () {
        Batch::factory(10)->create();
        get($this->route)
            ->assertStatus(302)
            ->assertRedirectToRoute('filament.admin.auth.login');
    });

    // TODO: Implement Roles and Permissions
    // test('redirect to 403 Error Page page if unauthorized', function () {
    //     Batch::factory(10)->create();
    //     get($this->route)
    //         ->assertStatus(403)
    //         ->assertRedirectToRoute('filament.admin.auth.login');
    // });

    test('render the batches index page', function () {
        actingAs($this->user);
        get($this->route)
            ->assertSuccessful()
            ->assertSee('Batches');
    });

    test('display table of batches', function () {
        $data = Batch::factory(10)->create();

        livewire($this->component)
            ->assertCanSeeTableRecords($data)
            ->assertCountTableRecords(10)
            ->assertTableColumnExists('product.name')
            ->assertTableActionExists(EditAction::class);
    });
});

describe('Create', function () {
    beforeEach(function () {
        $this->component = ManageBatches::class;
        $this->user = User::factory()->create();
    });

    test('show dialog to create a new batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountAction('create')
            ->assertSee('Create batch')
            ->assertSee('Product');
    });

    test('create a new batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Batch::factory()->make()->toArray();

        $livewire->mountAction('create')
            ->setActionData($payload)
            ->callAction('create');

        assertDatabaseHas('batches', [
            'product_id' => $payload['product_id'],
        ]);
    });

    test('validation error', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = [
            'product_id' => null,
            'location_id' => null,
            'transactions.0.quantity' => null,
        ];

        $livewire->mountAction('create')
            ->setActionData($payload)
            ->callAction('create')
            ->assertHasActionErrors([
                'product_id' => ['required'],
                'location_id' => ['required'],
                'transactions.0.quantity' => ['required'],
            ]);
    });
});

describe('Edit', function () {
    beforeEach(function () {
        $this->component = ManageBatches::class;
        $this->user = User::factory()->create();
        $this->batch = Batch::factory()->create();
    });

    test('show dialog to edit a new batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(EditAction::class, $this->batch)
            ->assertSee('Edit batch');

    });

    test('edit a batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = Batch::factory()->make()->toArray();

        $livewire
            ->mountTableAction('edit', record: $this->batch)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->batch)
            ->assertHasNoTableActionErrors();

        assertDatabaseHas('batches', [
            'location_id' => $payload['location_id'],
        ]);
    });

    test('validation error', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $payload = ['location_id' => null];

        $livewire
            ->mountTableAction('edit', record: $this->batch)
            ->setTableActionData($payload)
            ->callTableAction(EditAction::class, $this->batch)
            ->assertHasTableActionErrors(['location_id' => ['required']]);
    });
});

describe('Delete', function () {
    beforeEach(function () {
        $this->component = ManageBatches::class;
        $this->user = User::factory()->create();
        $this->batch = Batch::factory()->create();
    });

    test('show dialog to delete a batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire->mountTableAction(DeleteAction::class, $this->batch)
            ->assertSee('Delete batch');
    });

    test('delete a batch', function () {
        actingAs($this->user);
        $livewire = livewire($this->component);

        $livewire
            ->mountTableAction(DeleteAction::class, record: $this->batch)
            ->callTableAction(DeleteAction::class, $this->batch)
            ->assertHasNoTableActionErrors();

        assertSoftDeleted('batches', [
            'product_id' => $this->batch->product_id,
        ]);
    });
});

describe('View', function () {
    beforeEach(function () {
        $this->component = ManageBatches::class;
        $this->user = User::factory()->create();
        $this->batch = Batch::factory()->create();
    });

    test('should view a batch', function () {
        actingAs($this->user);

        $livewire = livewire($this->component, [
            'record' => $this->batch->getRouteKey(),
        ]);

        $livewire->mountTableAction('view', record: $this->batch)
            ->callTableAction('view', record: $this->batch)
            ->assertSee([
                'product_id' => $this->batch->product_id,
            ]);
    });
});
