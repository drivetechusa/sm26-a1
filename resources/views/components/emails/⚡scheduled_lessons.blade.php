<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendScheduledLessons()
    {
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\ScheduledLessons($this->student));
        Flux::toast('Scheduled Lessons have been sent.');
    }
};
?>

<flux:navmenu.item wire:click="sendScheduledLessons">Scheduled Lessons</flux:navmenu.item>
