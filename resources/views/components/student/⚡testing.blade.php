<?php

use App\Models\Student;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    use \Livewire\WithPagination;

    public Student $student;

    #[Computed]
    public function tests()
    {
        $query = \App\Models\Tpttest::query()->where('student_id', $this->student->id);
        return $query->paginate(4);
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-4">
        <livewire:tests.create :student="$student" @test-created="$refresh"/>
    </div>
    <flux:table :paginate="$this->tests">
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Route</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Receipt</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->tests as $test)
                <livewire:rows.test :test="$test" :key="$test->id"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
