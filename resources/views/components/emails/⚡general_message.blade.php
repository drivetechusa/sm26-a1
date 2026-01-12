<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    #[\Livewire\Attributes\Validate('required')]
    public $message = '';

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendMessage()
    {
        $this->validate();
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\GeneralMessage($this->message));
        Flux::toast('Message has been sent.');
        Flux::modals()->close();
    }

};
?>

<div>
    <flux:modal.trigger name="general-message">
        <flux:navmenu.item>Send Message</flux:navmenu.item>
    </flux:modal.trigger>
    <flux:modal name="general-message" class="w-1/3">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Send Message</flux:heading>
                <flux:text class="mt-2">This message will be sent to all emails on the account.</flux:text>
            </div>
            <form wire:submit="sendMessage" class="space-y-6">
                <flux:textarea label="Message" wire:model="message"/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Send Message</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
