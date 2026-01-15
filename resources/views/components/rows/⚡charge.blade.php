<?php

use App\Models\Charge;
use Livewire\Component;

new class extends Component {
    public Charge $charge;
    public \App\Livewire\Forms\ChargeForm $form;

    public function mount()
    {
        $this->form->setCharge($this->charge);
    }

    public function editCharge()
    {
        $this->form->update();

        $this->form->reset();
        Flux::toast('Charge updated.');
        Flux::modals()->close();
    }
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
                <flux:modal.trigger :name="'edit-charge-' . $charge->id">
                    <flux:menu.item icon="pencil-square">Edit Charge</flux:menu.item>
                </flux:modal.trigger>
                <flux:modal.trigger :name="'remove-charge-' . $charge->id">
                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>
        <flux:modal :name="'edit-charge-' . $charge->id" class="md:w-2/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Charge</flux:heading>
                    {{--                    <flux:text class="mt-2">Edit lesson for {{ $lesson->student->firstname }} {{ $lesson->student->lastname }}.</flux:text>--}}
                </div>
                <form wire:submit="editCharge" class="space-y-6">
                    <flux:input type="number" label="Amount" wire:model="form.amount"/>
                    <flux:select label="Reason" wire:model="form.reason" placeholder="Select a reason...">
                        <flux:select.option>Select a reason...</flux:select.option>
                        <flux:select.option>Course A Enrollment</flux:select.option>
                        <flux:select.option>Course B Enrollment</flux:select.option>
                        <flux:select.option>Course C Enrollment</flux:select.option>
                        <flux:select.option>LxL Enrollment</flux:select.option>
                        <flux:select.option>Roper Evaluation</flux:select.option>
                        <flux:select.option>LADS Hand Training</flux:select.option>
                        <flux:select.option>Point Reduction Enrollment</flux:select.option>
                        <flux:select.option>Road Test</flux:select.option>
                        <flux:select.option>Knowledge Test</flux:select.option>
                        <flux:select.option>Document Fee</flux:select.option>
                        <flux:select.option>SNS Fee</flux:select.option>
                        <flux:select.option>Reinstatement Fee</flux:select.option>
                        <flux:select.option>Refund</flux:select.option>
                    </flux:select>
                    <div class="flex">
                        <flux:spacer/>

                        <flux:button type="submit" variant="primary">Update Charge</flux:button>
                    </div>
                </form>

            </div>
        </flux:modal>
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
