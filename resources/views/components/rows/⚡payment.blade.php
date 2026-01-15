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
                <flux:modal.trigger :name="'remove-payment-' . $payment->id">
                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>
        <flux:modal :name="'remove-payment-' . $payment->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Remove Payment?</flux:heading>
                    <flux:text class="mt-2">
                        You're about to remove this payment.<br>
                        This action can not be undone.
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="$parent.removePayment({{$payment->id}})" variant="danger">Remove Payment</flux:button>
                </div>
            </div>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>

