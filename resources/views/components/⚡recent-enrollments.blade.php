<?php

use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Enums\StudentStatus;

new class extends Component {
    use \Livewire\WithPagination;

    #[Computed]
    public function enrollments()
    {
        $query = Student::query()->whereIn('status', [StudentStatus::ENROLLED, StudentStatus::HOLD_FOR_PAYMENT, StudentStatus::HOLD_FOR_PAPERWORK])->where('created_at', '>', today()->subDays(45))->orderBy('created_at', 'desc');
        return $query->paginate('4', pageName: 'enrollments');
    }
};
?>

<div class="py-2 px-3">
    <h1>Recent Enrollments</h1>
    <flux:table :paginate="$this->enrollments">
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Type</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->enrollments as $student)
                <flux:table.row>
                    <flux:table.cell><a href="{{route('students.show', ['id' => $student->id])}}">{{ $student->id }}</a></flux:table.cell>
                    <flux:table.cell>{{ $student->lastname }}, {{$student->firstname}}</flux:table.cell>
                    <flux:table.cell>{{ $student->type }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
