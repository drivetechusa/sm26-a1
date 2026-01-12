<?php

use App\Models\Charge;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Student $student;

    #[Computed]
    public function charges()
    {
        $query = Charge::query()->where('student_id', $this->student->id)->orderBy('entered', 'desc');
        return $query->paginate(7, pageName: 'charges-page');
    }
};
?>

<div>
    <span>Charges</span>
    <flux:table :paginate="$this->charges">
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Amount</flux:table.column>
            <flux:table.column>Reason</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->charges as $charge)
            <livewire:rows.charge :charge="$charge" :key="$charge->id" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
