<?php

use App\Models\Employee;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Student $student;
    #[Validate('required')]
    public $instructor_id = null;

    public function mount($id)
    {
        $this->student = Student::find($id);
        $this->instructor_id = $this->student->instructor_id;
    }

    public function showModal()
    {
        Flux::modal('assign-instructor')->show();
    }

    public function assignInstructor()
    {
        $this->validate();
        $this->student->instructor_id = $this->instructor_id;
        $this->student->save();

        $this->dispatch("student-updated.{$this->student->id}");
        Flux::modals()->close();
    }

    #[Computed]
    public function instructors()
    {
        $query = Employee::query()->whereNotNull('inst_license')->where('active', true);
        return $query->get();
    }
};
?>

<div>
    <flux:navmenu.item icon="user" wire:click="showModal">Assign Instructor</flux:navmenu.item>
    @teleport('body')
    <flux:modal name="assign-instructor" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Assign Instructor</flux:heading>
            </div>
            <form wire:submit="assignInstructor" class="space-y-4">
                <flux:select label="Instructor" wire:model="instructor_id">
                    <option value="">Select Instructor</option>
                    @foreach ($this->instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </flux:select>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Assign Instructor</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    @endteleport
</div>
