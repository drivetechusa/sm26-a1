<?php

use App\Models\Letter;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination, \App\Traits\SearchableTable;

    #[Computed]
    public function letters()
    {
        $query = Letter::query();
        return $query->paginate(15);
    }
};
?>

<div class="space-y-4">

    <p>Letter List</p>
    <div class="flex gap-2">
        <flux:input wire:model.live.debounce.2s="search" placeholder="Search" icon="magnifying-glass"/>
    </div>
    <div class="w-full">
        <flux:table :paginate="$this->letters">
            <flux:table.columns>
                <flux:table.column>ID</flux:table.column>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Body</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->letters as $letter)
                    <livewire:rows.letter :letter="$letter" key="{{$letter->id}}"/>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
