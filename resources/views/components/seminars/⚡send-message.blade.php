<?php

use App\Models\Seminar;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    #[Validate('required')]
    public $message;

    public function sendSeminarMessage()
    {
        $this->validate();
        $users = $this->seminar->emails;
        $chunks = array_chunk($users, 45);
        //Mail::to(config('app.school_email'))->bcc($this->seminar->emails->toArray())->send(new \App\Mail\GeneralMessage($this->message));
        foreach($chunks as $chunk)
        {
            Mail::to(config('app.school_email'))->bcc($chunk)->send(new \App\Mail\GeneralMessage($this->message));
        }

        Flux::toast('Message has been sent!');
        Flux::modals()->close();
    }
};
?>

<div>
    <flux:modal.trigger :name="'send-class-message-' . $this->seminar->id">
        <flux:menu.item icon="envelope">Send Message</flux:menu.item>
    </flux:modal.trigger>
@teleport('body')
    <flux:modal :name="'send-class-message-' . $this->seminar->id" class="md:w-1/3">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Send Message to Entire Class {{$this->seminar->id}}</flux:heading>
                <flux:text class="mt-2">Goes to all emails on account.</flux:text>
            </div>
            <form wire:submit="sendSeminarMessage" class="space-y-6">
                <flux:textarea label="Message" wire:model="message"/>
                <div class="flex">
                    <flux:spacer/>

                    <flux:button type="submit" variant="primary">Send Message</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    @endteleport
</div>
