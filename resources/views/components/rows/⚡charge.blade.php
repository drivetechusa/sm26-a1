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
                <flux:modal.trigger :name="'remove-charge-' . $charge->id">
                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>
        <flux:modal :name="'remove-charge-' . $charge->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Remove Charge?</flux:heading>
                    <flux:text class="mt-2">
                        You're about to remove this charge.<br>
                        This action can not be undone.
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="$parent.removeCharge({{$charge->id}})" variant="danger">Remove Charge</flux:button>
                </div>
            </div>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>

