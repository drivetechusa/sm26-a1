<?php

use App\Models\Charge;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Student $student;
    #[Validate('required')]
    public ?string $reason;
    #[Validate('required')]
    public ?float $amount;

    #[Computed]
    public function charges()
    {
        $query = Charge::query()->where('student_id', $this->student->id)->orderBy('entered', 'desc');
        return $query->paginate(7, pageName: 'charges-page');
    }

    public function addCharge()
    {
        $this->validate();

        $this->student->charges()->create([
            'amount' => $this->amount,
            'reason' => $this->reason,
            'entered' => now(),
            'employee_id' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        Flux::toast('Charge added.');
        Flux::modals()->close();
    }

    public function removeCharge($id)
    {
        $charge = Charge::findOrFail($id);
        $charge->delete();

        Flux::modals()->close();
        Flux::toast('Charge Removed');
    }
};
?>

<div>
    <span>Charges</span>
    <div>
        <flux:modal.trigger name="add-charge">
            <flux:button size="sm" variant="filled">Add Charge</flux:button>
        </flux:modal.trigger>

        <flux:modal name="add-charge" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add Charge</flux:heading>
                </div>
                <form wire:submit="addCharge" class="space-y-6">
                    <flux:input type="number" label="Amount" wire:model="amount"/>
                    <flux:select label="Reason" wire:model="reason" placeholder="Select a reason...">
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

                        <flux:button type="submit" variant="primary">Add Charge</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </div>
    <flux:table :paginate="$this->charges">
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Amount</flux:table.column>
            <flux:table.column>Reason</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->charges as $charge)
                <livewire:rows.charge :charge="$charge" :key="$charge->id"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
