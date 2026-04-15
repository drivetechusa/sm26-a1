<?php

use App\Models\WebNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination, \App\Traits\SearchableTable;

    public string $message = '';
    public bool $isActive = true;

    #[Computed]
    public function notifications()
    {
        $query = WebNotification::query();
        return $query->paginate(15);
    }

    public function createNotification(): void
    {
        $this->validate([
            'message' => ['required', 'string', 'max:65535'],
            'isActive' => ['boolean'],
        ]);

        WebNotification::create([
            'message' => $this->message,
            'is_active' => $this->isActive,
        ]);

        $this->reset('message', 'isActive');
        $this->isActive = true;
        $this->modal('create-notification')->close();
        unset($this->notifications);
    }
};
?>

<div class="space-y-4">

    <div class="flex items-center justify-between">
        <p>Notification List</p>
        <flux:button variant="primary" x-on:click="$flux.modal('create-notification').show()">New Notification</flux:button>
    </div>

    <div class="w-full">
        <flux:table :paginate="$this->notifications">
            <flux:table.columns>
                <flux:table.column>ID</flux:table.column>
                <flux:table.column>Message</flux:table.column>
                <flux:table.column>Active</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->notifications as $notification)
                    <livewire:rows.notification :notification="$notification" key="{{$notification->id}}" @notification-removed="$refresh"/>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="create-notification" class="w-full max-w-md">
        <flux:heading size="lg">New Notification</flux:heading>

        <form wire:submit="createNotification" class="mt-4 space-y-4">
            <flux:field>
                <flux:label>Message</flux:label>
                <flux:textarea wire:model="message" rows="4" placeholder="Enter notification message..."/>
                <flux:error name="message"/>
            </flux:field>

            <flux:field>
                <flux:switch wire:model="isActive" label="Active"/>
                <flux:error name="isActive"/>
            </flux:field>

            <div class="flex justify-end gap-2">
                <flux:button x-on:click="$flux.modal('create-notification').close()">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Create</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
