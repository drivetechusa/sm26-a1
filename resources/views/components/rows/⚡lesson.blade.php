<?php

use App\Models\Lesson;
use Livewire\Component;

new class extends Component {
    public Lesson $lesson;
};
?>

<flux:table.row>
    <flux:table.cell>{{$lesson->start_time->format('m/d/y')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->type}}</flux:table.cell>
    <flux:table.cell>{{$lesson->start_time->format('H:ia')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_time->format('H:ia')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_time->diffInHours($lesson->start_time, true)}}</flux:table.cell>
    <flux:table.cell>{{$lesson->begin_mileage}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_mileage}}</flux:table.cell>
    <flux:table.cell>{{ $lesson->end_mileage - $lesson->begin_mileage }}</flux:table.cell>
    <flux:table.cell>{{$lesson->instructor->name}}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>

            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
