<?php

use App\Livewire\Forms\VehicleForm;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component
{
    public Vehicle $vehicle;

    public VehicleForm $form;

    public function mount()
    {
        $this->vehicle = new Vehicle();
        $this->vehicle->active = true;
    }

    public function save()
    {
        $vehicle = $this->form->store();

        return $this->redirect(route('vehicles.index'), navigate: true);
    }
};
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <flux:heading size="xl">Create New Vehicle</flux:heading>
        <flux:subheading>Enter vehicle information to create a new vehicle record.</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-8">
        {{-- Basic Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Basic Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <flux:field>
                    <flux:label required>Name</flux:label>
                    <flux:input wire:model="form.name" placeholder="Vehicle name" />
                    <flux:error name="form.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Year</flux:label>
                    <flux:input type="number" wire:model="form.year" placeholder="2024" />
                    <flux:error name="form.year" />
                </flux:field>

                <flux:field>
                    <flux:label>Make</flux:label>
                    <flux:input wire:model="form.make" placeholder="Toyota, Ford, etc." />
                    <flux:error name="form.make" />
                </flux:field>

                <flux:field>
                    <flux:label>Model</flux:label>
                    <flux:input wire:model="form.model" placeholder="Camry, F-150, etc." />
                    <flux:error name="form.model" />
                </flux:field>

                <flux:field>
                    <flux:label>Active</flux:label>
                    <flux:checkbox wire:model="form.active" label="Vehicle is active" />
                    <flux:error name="form.active" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Registration & Identification Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Registration & Identification</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <flux:field>
                    <flux:label>VIN</flux:label>
                    <flux:input wire:model="form.vin" placeholder="Vehicle identification number" />
                    <flux:error name="form.vin" />
                </flux:field>

                <flux:field>
                    <flux:label>Tag Number</flux:label>
                    <flux:input wire:model="form.tag_number" placeholder="License plate number" />
                    <flux:error name="form.tag_number" />
                </flux:field>

                <flux:field>
                    <flux:label>Current Mileage</flux:label>
                    <flux:input type="number" step="0.1" wire:model="form.mileage" placeholder="0.0" />
                    <flux:error name="form.mileage" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Purchase Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Purchase Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <flux:field>
                    <flux:label>Date Purchased</flux:label>
                    <flux:input type="date" wire:model="form.date_purchased" />
                    <flux:error name="form.date_purchased" />
                </flux:field>

                <flux:field>
                    <flux:label>Purchase Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.purchase_price" placeholder="0.00" />
                    <flux:error name="form.purchase_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Date Sold</flux:label>
                    <flux:input type="date" wire:model="form.date_sold" />
                    <flux:error name="form.date_sold" />
                </flux:field>

                <flux:field>
                    <flux:label>Selling Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.selling_price" placeholder="0.00" />
                    <flux:error name="form.selling_price" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" href="{{ route('vehicles.index') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Create Vehicle
            </flux:button>
        </div>
    </form>
</div>