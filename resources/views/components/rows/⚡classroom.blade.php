<?php

use App\Models\Classroom;
use Livewire\Component;

new class extends Component {
    public Classroom $classroom;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $classroom->name }}</flux:table.cell>
    <flux:table.cell>{{ $classroom->teen_price }}</flux:table.cell>
    <flux:table.cell>{{ $classroom->ext_price }}</flux:table.cell>
    <flux:table.cell>{{ $classroom->adv_price }}</flux:table.cell>
    <flux:table.cell>{{ $classroom->point_price }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:menu.item href="/classrooms/{{$classroom->id}}/edit" icon="pencil-square">Edit</flux:menu.item>



            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
