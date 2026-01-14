<?php

use App\Models\Seminar;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function sendClassLogs()
    {
        $students = $this->seminar->students;
        foreach ($students as $student) {
            \Illuminate\Support\Facades\Mail::to($student->notification_emails)->send(new \App\Mail\SCActivityLogEmail($student));
        }
        Flux::toast('Class Logs sent.');
        Flux::modals()->close();
    }
};
?>

<flux:menu.item icon="envelope" wire:click="sendClassLogs">Class Logs</flux:menu.item>
