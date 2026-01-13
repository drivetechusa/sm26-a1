<?php

use App\Models\Payment;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    public Student $student;
    #[Validate('required', 'numeric')]
    public ?float $amount = null;
    #[Validate('required')]
    public $date = null;
    #[Validate('required')]
    public ?string $type = null;
    #[Validate('nullable')]
    public ?string $check_number = null;
    #[Validate('nullable')]
    public ?string $auth_number = null;
    #[Validate('required')]
    public ?string $remarks = '';

    #[Computed]
    public function payments()
    {
        $query = Payment::query()->where('student_id', $this->student->id)->orderBy('date', 'desc');
        return $query->paginate(7, pageName: 'payments-page');
    }

    public function addPayment()
    {
        $this->validate();
        $this->student->payments()->create([
            'amount' => $this->amount,
            'date' => $this->date,
            'type' => $this->type,
            'check_number' => $this->check_number,
            'auth_number' => $this->auth_number,
            'employee_id' => auth()->id(),
            'last_update' => now(),
            'remarks' => $this->remarks,
            'updated_by' => auth()->id()
        ]);

        Flux::toast('Payment added.');
        Flux::modals()->close();
    }
};
?>

<div>
    <span>Payments</span>
    <div>
        <flux:modal.trigger name="add-payment">
            <flux:button size="sm" variant="filled">Add Payment</flux:button>
        </flux:modal.trigger>

        <flux:modal name="add-payment" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add Payment</flux:heading>
                </div>
                <form wire:submit="addPayment" class="space-y-6">
                    <flux:input type="number" label="Amount" wire:model="amount"/>
                    <flux:input type="date" label="Date" wire:model="date"/>
                    <flux:select label="Type" wire:model="type" placeholder="Select a payment type...">
                        <flux:select.option>Select a payment type...</flux:select.option>
                        <flux:select.option>Check</flux:select.option>
                        <flux:select.option>Credit Card</flux:select.option>
                        <flux:select.option>Cash</flux:select.option>
                        <flux:select.option>Credit</flux:select.option>
                    </flux:select>
                    <flux:input type="text" label="Check Number" wire:model="check_number"/>
                    <flux:input type="text" label="Authorization Number" wire:model="auth_number"/>
                    <flux:select label="Reason" wire:model="remarks" placeholder="Select a reason...">
                        <flux:select.option>Select a reason...</flux:select.option>
                        <flux:select.option>Balance Payment</flux:select.option>
                        <flux:select.option>Document Fee</flux:select.option>
                        <flux:select.option>Course A Enrollment</flux:select.option>
                        <flux:select.option>Course B Enrollment</flux:select.option>
                        <flux:select.option>Course C Enrollment</flux:select.option>
                        <flux:select.option>LxL Enrollment</flux:select.option>
                        <flux:select.option>Roper Evaluation</flux:select.option>
                        <flux:select.option>LADS Hand Training</flux:select.option>
                        <flux:select.option>Point Reduction Enrollment</flux:select.option>
                        <flux:select.option>SNS Fee</flux:select.option>
                        <flux:select.option>Refund</flux:select.option>
                    </flux:select>
                    <div class="flex">
                        <flux:spacer/>

                        <flux:button type="submit" variant="primary">Add Payment</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </div>
    <flux:table :paginate="$this->payments">
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Amount</flux:table.column>
            <flux:table.column>Reason</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->payments as $payment)
                <livewire:rows.payment :payment="$payment" :key="$payment->id"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
