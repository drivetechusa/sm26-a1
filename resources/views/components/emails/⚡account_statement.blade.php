<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {


    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendAccountStatementEmail()
    {
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\AccountStatementEmail($this->student));
        Flux::toast('Account Statement Email has been sent.');
    }
};
?>

<flux:navmenu.item wire:click="sendAccountStatementEmail">Account Statement</flux:navmenu.item>
