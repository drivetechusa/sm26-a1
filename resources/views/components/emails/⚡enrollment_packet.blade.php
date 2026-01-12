<?php

use App\Models\Seminar;
use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;
    public Seminar $seminar;

    public function mount()
    {

    }

    public function emailEnrollmentPacket()
    {
        Mail::to($this->student->notification_emails)->send(new \App\Mail\EnrollmentPacket($this->seminar, $this->student));
        Flux::toast('Enrollent Packet sent!');
    }
};
?>

<flux:menu.item wire:click="emailEnrollmentPacket" icon="envelope">Email Packet</flux:menu.item>
