<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Note $note;
};
?>

<flux:table.row>
    <flux:table.cell><a href="{{route('students.show', ['id' => $note->student_id])}}">{{ $note->student_id }}</a></flux:table.cell>
    <flux:table.cell>{{ $note->created->format('m/d/y H:ia') }}</flux:table.cell>
    <flux:table.cell>{{ $note->note }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>

            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
