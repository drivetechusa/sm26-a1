<?php

use App\Models\WebNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination, \App\Traits\SearchableTable;

    #[Computed]
    public function notifications()
    {
        $query = WebNotification::query();
        return $query->paginate(15);
    }
};
?>

<div class="space-y-4">

    <p>Notification List</p>
    {{--    <div class="flex gap-2">--}}
    {{--        <flux:input wire:model.live.debounce.2s="search" placeholder="Search" icon="magnifying-glass"/>--}}
    {{--    </div>--}}
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
                    <livewire:rows.notification :notification="$notification" key="{{$notification->id}}"/>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
