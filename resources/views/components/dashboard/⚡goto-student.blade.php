<?php

use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Validate('required|exists:students,id')]
    public $student_id;

    public function gotoStudent()
    {
        $this->validate();

        return redirect()->route('students.show', $this->student_id);
    }
};
?>

<div class="flex flex-col space-y-3">
    <flux:input wire:model="student_id" label="Student ID" />
    <flux:button wire:click="gotoStudent">Go to Student</flux:button>
</div>
