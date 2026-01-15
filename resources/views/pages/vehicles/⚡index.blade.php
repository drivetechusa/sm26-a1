<?php

use App\Traits\SearchableTable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Vehicles List')]
class extends Component {
    use WithPagination, SearchableTable;

    public $active = 1;

    #[Computed]
    public function vehicles()
    {
        $query = \App\Models\Vehicle::query();
        $query->where('active', $this->active);
        return $query->paginate(10);
    }

    public function toggleActive()
    {
        $this->active == 1 ? $this->active = 0 : $this->active = 1;
    }
};
?>

<div class="space-y-4">

    <p>{{$this->active ? 'Active' : 'Archived'}} Vehicles List</p>
    <div class="flex gap-2">
        <flux:input wire:model.live.debounce.2s="search" placeholder="Search" icon="magnifying-glass"/>

        <flux:button wire:click="toggleActive" variant="primary">{{$this->active ? 'Archived' : 'Active'}}</flux:button>
        <flux:button href="{{route('vehicles.create')}}" variant="primary">New Vehicle</flux:button>
    </div>
    <div class="w-full">
        <flux:table :paginate="$this->vehicles">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Year</flux:table.column>
                <flux:table.column>Make/Model</flux:table.column>
                <flux:table.column>VIN</flux:table.column>
                <flux:table.column>Tag#</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->vehicles as $vehicle)
                    <livewire:rows.vehicle :vehicle="$vehicle" key="{{$vehicle->id}}"/>

                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
