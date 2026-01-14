<?php

use App\Models\Seminar;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function sendWorkbook()
    {
        $users = $this->seminar->emails;
        $chunks = array_chunk($users, 45);
        foreach($chunks as $chunk)
        {
            Mail::to(config('app.school_email'))->bcc($chunk)->send(new \App\Mail\Workbook());
        }

        Flux::toast('Workbooks have been sent!');
        Flux::modals()->close();
    }
};
?>

<flux:menu.item icon="envelope" wire:click="sendWorkbook">Workbooks</flux:menu.item>
