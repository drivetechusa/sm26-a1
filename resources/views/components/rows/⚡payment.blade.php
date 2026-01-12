<?php

use App\Models\Payment;
use Livewire\Component;

new class extends Component {
    public Payment $payment;
};
?>

<flux:table.row>
    <flux:table.cell>{{ $payment->date->format('m/d/y') }}</flux:table.cell>
    <flux:table.cell>{{ $payment->type }}</flux:table.cell>
    <flux:table.cell>{{ $payment->amount }}</flux:table.cell>
    <flux:table.cell>{{ $payment->remarks }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>

            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
