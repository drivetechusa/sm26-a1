<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Employee $employee;
};
?>

<div>
    {{ $employee->name }}
</div>
