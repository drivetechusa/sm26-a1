<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Vehicle $vehicle;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $vehicle->name }}</flux:table.cell>
    <flux:table.cell>{{ $vehicle->year }}</flux:table.cell>
    <flux:table.cell>{{ $vehicle->make }} {{ $vehicle->model }}</flux:table.cell>
    <flux:table.cell>{{ $vehicle->vin }}</flux:table.cell>
    <flux:table.cell>{{ $vehicle->tag_number }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
            <flux:menu>
                <flux:menu.item href="{{route('vehicles.show', ['vehicle' => $vehicle])}}">Show</flux:menu.item>
                <flux:menu.item href="{{route('vehicles.edit', ['id' => $vehicle->id])}}" icon="pencil-square">Edit</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
