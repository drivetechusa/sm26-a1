<?php

use App\Models\Note;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Student $student;

    #[Computed]
    public function notes()
    {
        $query = Note::query()->where('student_id', $this->student->id);
        $query->orderBy('created', 'desc');
        return $query->paginate(7);
    }
};
?>

<div>
    @island
    <flux:table :paginate="$this->notes">
        <flux:table.columns>
            <flux:table.column>Student</flux:table.column>
            <flux:table.column>Created</flux:table.column>
            <flux:table.column>Note</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->notes as $note)
                <livewire:rows.note :note="$note" key="{{$note->id}}"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
    @endisland
</div>
