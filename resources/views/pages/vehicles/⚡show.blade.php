<?php

use App\Models\MaintenanceType;
use App\Models\Vehicle;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Vehicle Details')]
class extends Component {
    public Vehicle $vehicle;

    #[Validate('required|exists:maintenancetypes,id')]
    public $maintenancetype_id = '';

    #[Validate('required|date')]
    public $date = '';

    #[Validate('nullable|string|max:255')]
    public $description = '';

    #[Validate('nullable|numeric|min:0')]
    public $mileage = '';

    #[Validate('nullable|string|max:255')]
    public $vendor = '';

    #[Validate('nullable|numeric|min:0')]
    public $cost = '';

    #[Computed]
    public function maintenances()
    {
        return $this->vehicle->maintenances()->with('maintenanceType')->latest('date')->get();
    }

    #[Computed]
    public function maintenanceTypes()
    {
        return MaintenanceType::orderBy('name')->get();
    }

    public function saveMaintenance()
    {
        $this->validate();

        $this->vehicle->maintenances()->create([
            'maintenancetype_id' => $this->maintenancetype_id,
            'date' => $this->date,
            'description' => $this->description ?: null,
            'mileage' => $this->mileage ?: null,
            'vendor' => $this->vendor ?: null,
            'cost' => $this->cost ?: null,
            'updated_by' => auth()->id(),
            'employee_id' => auth()->id(),
            'last_update' => now()
        ]);

        $this->reset(['maintenancetype_id', 'date', 'description', 'mileage', 'vendor', 'cost']);
        unset($this->maintenances);

        Flux::toast('Maintenance record added.');
        Flux::modals()->close();
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ $vehicle->name }}</flux:heading>
        <div class="flex gap-2">
            <flux:modal.trigger name="add-maintenance">
                <flux:button variant="primary">Add Maintenance</flux:button>
            </flux:modal.trigger>
            <flux:button href="{{ route('vehicles.edit', $vehicle) }}" variant="primary">Edit Vehicle</flux:button>
            <flux:button href="{{ route('vehicles.index') }}" variant="ghost">Back to List</flux:button>
        </div>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">Vehicle Information</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Year / Make / Model</flux:text>
                <flux:text class="font-medium">{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}</flux:text>
            </div>
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">VIN</flux:text>
                <flux:text class="font-medium">{{ $vehicle->vin ?: '—' }}</flux:text>
            </div>
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Tag Number</flux:text>
                <flux:text class="font-medium">{{ $vehicle->tag_number ?: '—' }}</flux:text>
            </div>
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Mileage</flux:text>
                <flux:text class="font-medium">{{ number_format($vehicle->current_mileage ?? $vehicle->mileage ?? 0, 1) }}</flux:text>
            </div>
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Status</flux:text>
                <flux:badge :color="$vehicle->active ? 'green' : 'zinc'">{{ $vehicle->active ? 'Active' : 'Archived' }}</flux:badge>
            </div>
            @if($vehicle->date_purchased)
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Date Purchased</flux:text>
                    <flux:text class="font-medium">{{ $vehicle->date_purchased->format('M j, Y') }}</flux:text>
                </div>
            @endif
            @if($vehicle->purchase_price)
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Purchase Price</flux:text>
                    <flux:text class="font-medium">${{ number_format($vehicle->purchase_price, 2) }}</flux:text>
                </div>
            @endif
            @if($vehicle->date_sold)
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Date Sold</flux:text>
                    <flux:text class="font-medium">{{ $vehicle->date_sold->format('M j, Y') }}</flux:text>
                </div>
            @endif
            @if($vehicle->selling_price)
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Selling Price</flux:text>
                    <flux:text class="font-medium">${{ number_format($vehicle->selling_price, 2) }}</flux:text>
                </div>
            @endif
        </div>
    </flux:card>

    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">Maintenance History</flux:heading>
        </div>

        @if($this->maintenances->isEmpty())
            <flux:text class="text-zinc-500 dark:text-zinc-400">No maintenance records found for this vehicle.</flux:text>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column>Mileage</flux:table.column>
                    <flux:table.column>Vendor</flux:table.column>
                    <flux:table.column>Cost</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($this->maintenances as $maintenance)
                        <flux:table.row wire:key="maintenance-{{ $maintenance->id }}">
                            <flux:table.cell>{{ $maintenance->date?->format('M j, Y') ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $maintenance->maintenanceType?->name ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $maintenance->description ?: '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $maintenance->mileage ? number_format($maintenance->mileage, 1) : '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $maintenance->vendor ?: '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $maintenance->cost ? '$' . number_format($maintenance->cost, 2) : '—' }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </flux:card>

    <flux:modal name="add-maintenance" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Maintenance Record</flux:heading>
                <flux:text class="mt-2">Record maintenance performed on this vehicle.</flux:text>
            </div>
            <form wire:submit="saveMaintenance" class="space-y-4">
                <flux:select wire:model="maintenancetype_id" label="Type" placeholder="Select type...">
                    @foreach($this->maintenanceTypes as $type)
                        <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="date" type="date" label="Date" />

                <flux:input wire:model="description" label="Description" placeholder="Oil change, tire rotation, etc." />

                <flux:input wire:model="mileage" type="number" step="0.1" label="Mileage" placeholder="Current mileage" />

                <flux:input wire:model="vendor" label="Vendor" placeholder="Shop or service provider" />

                <flux:input wire:model="cost" type="number" step="0.01" label="Cost" placeholder="0.00" />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

