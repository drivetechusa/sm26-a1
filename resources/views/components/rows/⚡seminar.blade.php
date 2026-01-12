<?php

use App\Models\Seminar;
use App\Models\Lesson;
use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function remove()
    {
        $this->seminar->delete();
        $this->dispatch('seminar-removed');
    }

    public function completeSeminar()
    {
        $start_time = $this->seminar->date->format('Y-m-d') .' ' . config('app.classroom_start_time') . ':00';
        $end_time   = $this->seminar->date->format('Y-m-d') . ' ' . config('app.classroom_end_time') . ':00';
        foreach ($this->seminar->students as $student)
        {
            $student->lessons()->create([
                'type' => 'CR Complete',
                'employee_id' => $this->seminar->instructor->id,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'complete' => true,
                'hide' => false,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
        }

        Flux::toast('Class Students have been updated!');
    }
};
?>

<flux:table.row>
    <flux:table.cell>{{$seminar->date->format('m/d/y')}}</flux:table.cell>
    <flux:table.cell>{{$seminar->id}}</flux:table.cell>
    <flux:table.cell>{{$seminar->classroom->name}}</flux:table.cell>
    <flux:table.cell>{{optional($seminar->instructor)->name}}</flux:table.cell>
    <flux:table.cell>{{$seminar->class_type}}</flux:table.cell>
    <flux:table.cell>{{$seminar->students()->count()}}</flux:table.cell>
    <flux:table.cell>{{$seminar->sale_price}}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:menu.item href="/seminars/{{$seminar->id}}/edit" icon="pencil-square">Edit</flux:menu.item>
                <flux:menu.item href="/documents/roster/{{$seminar->id}}" target="_blank" icon="clipboard-document-list">Roster</flux:menu.item>
                <flux:menu.item wire:click="completeSeminar" icon="shield-check">Class Complete</flux:menu.item>
                <flux:menu.item href="/documents/coversheets/{{$seminar->id}}" target="_blank" icon="identification">Coversheets</flux:menu.item>
                <flux:menu.item icon="envelope">Scheduling Instructions</flux:menu.item>
                <flux:menu.item icon="envelope">Workbook</flux:menu.item>
                <flux:menu.item icon="envelope">Zoom Test</flux:menu.item>
                <flux:menu.item href="/documents/class_logs/{{$seminar->id}}" target="_blank" icon="printer">Class Logs</flux:menu.item>
                <flux:menu.item icon="envelope">Class Logs</flux:menu.item>
                <flux:menu.item icon="cloud-arrow-up">To Scheduler</flux:menu.item>
                <flux:menu.item icon="envelope">Message Class</flux:menu.item>
                @unless ($seminar->students()->count() > 0)
                    <flux:menu.item wire:click='remove' icon="minus-circle">Remove Seminar</flux:menu.item>
                @endunless
            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
