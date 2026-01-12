<?php

use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    #[Computed]
    public function inquiries()
    {
        $query = Student::query()->where('status', \App\Enums\StudentStatus::INQUIRED)->where('created_at','>', today()->subDays(45))->orderBy('created_at', 'desc');
        return $query->paginate('4', pageName: 'inquiries');
    }
};
?>

<div class="py-2 px-3">
    <h1>Recent Inquiries</h1>
    <flux:table :paginate="$this->inquiries">
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Type</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->inquiries as $student)
                <flux:table.row>
                    <flux:table.cell><a href="{{route('students.show', ['id' => $student->id])}}">{{ $student->id }}</a></flux:table.cell>
                    <flux:table.cell>{{ $student->lastname }}, {{$student->firstname}}</flux:table.cell>
                    <flux:table.cell>{{ $student->type }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
