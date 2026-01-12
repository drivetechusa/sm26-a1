<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Tpttest $test;
};
?>

<flux:table.row>
    <flux:table.cell>{{$test->date->format('m/d/y')}}</flux:table.cell>
    <flux:table.cell>{{$test->test_type}}</flux:table.cell>
    <flux:table.cell>{{$test->route}}</flux:table.cell>
    <flux:table.cell>{{$test->status}}</flux:table.cell>
    <flux:table.cell>{{$test->so_id}}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>

            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
