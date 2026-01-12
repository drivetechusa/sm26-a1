<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendSchedulingInstructions()
    {
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\SchedulingInstructions($this->student));
        Flux::toast('Scheduling Instructions have been sent.');
    }
};
?>

<flux:navmenu.item wire:click="sendSchedulingInstructions">Scheduling Instructions</flux:navmenu.item>
