<?php

use App\Models\Seminar;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function sendZoomTests()
    {
        $users = $this->seminar->emails;
        $chunks = array_chunk($users, 45);
        foreach($chunks as $chunk)
        {
            Mail::to(config('app.school_email'))->bcc($chunk)->send(new \App\Mail\ZoomTest());
        }

        Flux::toast('Zoom tests have been sent!');
        Flux::modals()->close();
    }
};
?>

<flux:menu.item icon="envelope" wire:click="sendZoomTests">Zoom Tests</flux:menu.item>
