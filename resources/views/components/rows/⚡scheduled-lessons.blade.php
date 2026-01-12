<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Scheduler\Lesson $lesson;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $lesson->start_time->format('m/d/y') }}</flux:table.cell>
    <flux:table.cell>Scheduled Lesson</flux:table.cell>
    <flux:table.cell>{{ $lesson->start_time->format('g:ia') }}</flux:table.cell>
    <flux:table.cell>{{ $lesson->end_time->format('g:ia') }}</flux:table.cell>
    <flux:table.cell>{{ $lesson->start_time->diffInHours($lesson->end_time, true) }}</flux:table.cell>
    <flux:table.cell></flux:table.cell>
    <flux:table.cell></flux:table.cell>
    <flux:table.cell></flux:table.cell>
    <flux:table.cell>{{ $lesson->instructor->full_name }}</flux:table.cell>
    <flux:table.cell></flux:table.cell>
</flux:table.row>
