<?php

use App\Models\Payment;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Payment $payment;
    public \App\Livewire\Forms\PaymentForm $form;


    public function mount()
    {
        $this->form->setPayment($this->payment);
    }

    public function editPayment()
    {
        $this->form->update();

        $this->form->reset();
        Flux::toast('Payment updated.');
        Flux::modals()->close();
    }
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
                <flux:modal.trigger :name="'edit-payment-' . $payment->id">
                    <flux:menu.item icon="pencil-square">Edit Payment</flux:menu.item>
                </flux:modal.trigger>
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
                    <flux:spacer/>
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="$parent.removePayment({{$payment->id}})" variant="danger">Remove Payment
                    </flux:button>
                </div>
            </div>
        </flux:modal>
        <flux:modal :name="'edit-payment-' . $payment->id" class="md:w-2/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Payment</flux:heading>
                    {{--                    <flux:text class="mt-2">Edit lesson for {{ $lesson->student->firstname }} {{ $lesson->student->lastname }}.</flux:text>--}}
                </div>
                <form wire:submit="editPayment" class="space-y-6">
                    <flux:input type="number" label="Amount" wire:model="form.amount"/>
                    <flux:input type="date" label="Date" wire:model="form.date"/>
                    <flux:select label="Type" wire:model="form.type" placeholder="Select a payment type...">
                        <flux:select.option>Select a payment type...</flux:select.option>
                        <flux:select.option>Check</flux:select.option>
                        <flux:select.option>Credit Card</flux:select.option>
                        <flux:select.option>Cash</flux:select.option>
                        <flux:select.option>Credit</flux:select.option>
                    </flux:select>
                    <flux:input type="text" label="Check Number" wire:model="form.check_number"/>
                    <flux:input type="text" label="Authorization Number" wire:model="form.auth_number"/>
                    <flux:select label="Reason" wire:model="form.remarks" placeholder="Select a reason...">
                        <flux:select.option>Select a reason...</flux:select.option>
                        <flux:select.option>Balance Payment</flux:select.option>
                        <flux:select.option>Course A Enrollment</flux:select.option>
                        <flux:select.option>Course B Enrollment</flux:select.option>
                        <flux:select.option>Course C Enrollment</flux:select.option>
                        <flux:select.option>Road Test</flux:select.option>
                        <flux:select.option>Knowledge Test</flux:select.option>
                        <flux:select.option>LxL Enrollment</flux:select.option>
                        <flux:select.option>Point Reduction Enrollment</flux:select.option>
                        <flux:select.option>Document Fee</flux:select.option>
                        <flux:select.option>SNS Fee</flux:select.option>
                        <flux:select.option>Refund</flux:select.option>
                    </flux:select>
                    <div class="flex">
                        <flux:spacer/>

                        <flux:button type="submit" variant="primary">Update Payment</flux:button>
                    </div>
                </form>

            </div>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
