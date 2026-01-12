<?php

use App\Models\Charge;
use Livewire\Component;

new class extends Component {
    public Charge $charge;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $charge->entered->format('m/d/y') }}</flux:table.cell>
    <flux:table.cell>{{ $charge->amount }}</flux:table.cell>
    <flux:table.cell>{{ $charge->reason }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>

            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
