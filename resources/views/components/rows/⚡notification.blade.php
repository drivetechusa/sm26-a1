<?php

use App\Models\WebNotification;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public WebNotification $notification;

    #[Validate('required')]
    public ?string $message = '';
    #[Validate('required|boolean')]
    public ?bool $is_active = true;

    public function mount()
    {
        $this->message = $this->notification->message;
        $this->is_active = $this->notification->is_active;
    }

    public function updateNotification(): void
    {
        $this->notification->message = $this->message;
        $this->notification->is_active = $this->is_active;
        $this->notification->save();

        Flux::modals()->close();
        Flux::toast('Notification updated.');
    }

    public function remove(): void
    {
        abort_if($this->notification->is_active, 403, 'Cannot delete an active notification.');

        $this->notification->delete();
        $this->dispatch('notification-removed');
    }
};
?>

<flux:table.row>
    <flux:table.cell>{{ $notification->id }}</flux:table.cell>

    <flux:table.cell
        class="max-w-xs md:max-w-lg overflow-hidden text-ellipsis whitespace-nowrap">{{ $notification->message }}
    </flux:table.cell>
    <flux:table.cell><x-active value="{{ $notification->is_active }}"/></flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:modal.trigger :name="'edit-notification-' . $notification->id">
                    <flux:menu.item>Edit notification</flux:menu.item>
                </flux:modal.trigger>
                @if (!$notification->is_active)
                    <flux:menu.item wire:click="remove" wire:confirm="Are you sure you want to delete this notification?" icon="trash">Delete</flux:menu.item>
                @endif
            </flux:menu>
        </flux:dropdown>
        <flux:modal :name="'edit-notification-' . $notification->id" class="md:w-1/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update notification</flux:heading>
                    <flux:text class="mt-2">Make changes notification.</flux:text>
                </div>
                <form wire:submit="updateNotification" class="space-y-6">
                    <flux:textarea label="Message" wire:model="message" rows="10"/>
                    <flux:checkbox label="Active" wire:model="is_active"/>

                    <div class="flex">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>

    </flux:table.cell>
</flux:table.row>
