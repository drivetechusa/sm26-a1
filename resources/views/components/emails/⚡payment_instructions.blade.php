<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendPaymentInstructions()
    {
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\PaymentInstructions($this->student));
        Flux::toast('Payment instructions have been sent.');
    }
};
?>

<flux:navmenu.item wire:click="sendPaymentInstructions">Payment Instructions</flux:navmenu.item>
