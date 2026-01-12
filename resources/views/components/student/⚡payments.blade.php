<?php

use App\Models\Payment;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    public Student $student;

    #[Computed]
    public function payments()
    {
        $query = Payment::query()->where('student_id', $this->student->id)->orderBy('date', 'desc');
        return $query->paginate(7, pageName: 'payments-page');
    }
};
?>

<div>
    <span>Payments</span>
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
                <livewire:rows.payment :payment="$payment" :key="$payment->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
