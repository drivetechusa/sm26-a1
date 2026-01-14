<?php

use App\Models\Seminar;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function sendInstructions()
    {
        $students = $this->seminar->students;
        foreach ($students as $student)
        {
            \Illuminate\Support\Facades\Mail::to($student->notification_emails)->send(new \App\Mail\SchedulingInstructions($student));
        }
        Flux::toast('Scheduling instructions sent.');
        Flux::modals()->close();
    }
};
?>

<flux:menu.item icon="envelope" wire:click="sendInstructions">Scheduling Instructions</flux:menu.item>
