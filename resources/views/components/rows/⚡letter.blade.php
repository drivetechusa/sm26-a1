<?php

use App\Models\Letter;
use Livewire\Component;

new class extends Component {
    public Letter $letter;
    public $name = '';
    public $body = '';

    public function mount()
    {
        $this->name = $this->letter->name;
        $this->body = $this->letter->body;
    }

    public function updateLetter()
    {
        $this->letter->name = $this->name;
        $this->letter->body = $this->body;
        $this->letter->save();

        Flux::modals()->close();
        Flux::toast('Letter updated.');
    }
};
?>

<flux:table.row>
    <flux:table.cell>{{ $letter->id }}</flux:table.cell>
    <flux:table.cell>{{ $letter->name }}</flux:table.cell>
    <flux:table.cell
        class="max-w-xs md:max-w-lg overflow-hidden text-ellipsis whitespace-nowrap">{{ $letter->body }}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:modal.trigger :name="'edit-letter-' . $letter->id">
                    <flux:menu.item>Edit letter</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>
        <flux:modal :name="'edit-letter-' . $letter->id" class="md:w-1/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update letter</flux:heading>
                    <flux:text class="mt-2">Make changes letter.</flux:text>
                </div>
                <form wire:submit="updateLetter" class="space-y-6">
                    <flux:input label="Name" wire:model="name"/>
                    <flux:textarea label="Body" wire:model="body" rows="10"/>

                    <div class="flex">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>

    </flux:table.cell>
</flux:table.row>
