<?php

use App\Models\Note;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Student $student;
    #[Validate('required')]
    public $addedNote = '';

    #[Computed]
    public function notes()
    {
        $query = Note::query()->where('student_id', $this->student->id);
        $query->orderBy('created', 'desc');
        return $query->paginate(7);
    }

    public function addNote()
    {
        $this->validate();
        $note = $this->student->notes()->create([
            'note' => $this->addedNote,
            'instructor_id' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);

        Flux::toast('Note added.');
        Flux::modals()->close();
    }
};
?>

<div>
    <div>
        <flux:modal.trigger name="add-note">
            <flux:button>Add Note</flux:button>
        </flux:modal.trigger>

        <flux:modal name="add-note" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add note</flux:heading>
                    <flux:text class="mt-2">These can be seen by the student.</flux:text>
                </div>
                <form wire:submit="addNote" class="space-y-6">
                    <flux:textarea wire:model="addedNote" label="Note"/>

                    <div class="flex">
                        <flux:spacer/>

                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </div>
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

</div>
