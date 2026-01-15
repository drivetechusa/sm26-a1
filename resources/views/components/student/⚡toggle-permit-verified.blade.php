<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function toggle()
    {
        $this->student->permit_verified = !$this->student->permit_verified;
        $this->student->save();

        Flux::toast('Contract toggled');

        $this->dispatch("student-updated.{$this->student->id}");
    }
};
?>

<flux:button size="xs" icon="arrows-up-down" variant="primary" wire:click="toggle" />
