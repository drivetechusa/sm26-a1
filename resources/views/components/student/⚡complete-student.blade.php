<?php

use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Student $student;
    #[Validate('required|date')]
    public $date;
    #[Validate('required|boolean')]
    public $rt_ready = false;
    #[Validate('required|boolean')]
    public $pdla = false;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function showModal()
    {
        Flux::modal('complete-student')->show();
    }

    public function completeStudent()
    {
        $this->validate();
        $this->student->date_completed = $this->date;
        $this->student->status = \App\Enums\StudentStatus::COMPLETE;
        $this->student->save();

        $note = 'Student Complete.';
        if ($this->rt_ready)
        {
            $note .= ' RT Ready.';
        }
        if ($this->pdla)
        {
            $note .= ' PDLA Given.';
        } else {
            $note .= ' NO PDLA Given.';
        }

        $this->student->notes()->create([
            'note' => $note,
            'instructor_id' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        Flux::modals('complete-student')->close();
        Flux::toast('Student Record Completed.');

        $this->dispatch('student-completed');
    }
};
?>
<div>
    <flux:navmenu.item icon="shield-check" wire:click="showModal">Complete Student</flux:navmenu.item>
    @teleport('body')
    <flux:modal name="complete-student" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Complete Student</flux:heading>
                <flux:text class="mt-2">Fill out to complete student.</flux:text>
            </div>
            <form wire:submit="completeStudent" class="space-y-4">
                <flux:input type="date" label="Date Completed" wire:model="date"/>
                <flux:checkbox label="RT Ready" wire:model="rt_ready"/>
                <flux:checkbox label="PDLA Given" wire:model="pdla"/>
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Complete Student</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    @endteleport
</div>

