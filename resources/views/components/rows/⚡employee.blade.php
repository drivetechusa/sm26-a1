<?php

use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Functions;

new
class extends Component {
    public Employee $employee;
};
?>

<flux:table.row>
    <flux:table.cell>{{$employee->id}}</flux:table.cell>
    <flux:table.cell>{{$employee->name}}</flux:table.cell>
    <flux:table.cell>{{$employee->email}}</flux:table.cell>
    <flux:table.cell>{{Functions::formatPhone($employee->phone)}}</flux:table.cell>
    <flux:table.cell>{{$employee->inst_license}}</flux:table.cell>
    <flux:table.cell><x-active :value="$employee->active" /></flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:menu.item href="{{route('employees.show', ['employee' => $employee])}}">Show</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
