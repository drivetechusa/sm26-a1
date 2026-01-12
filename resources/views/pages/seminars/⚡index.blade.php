<?php

use App\Models\Seminar;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $pastClasses = false;



    #[Computed]
    public function seminars()
    {
        $query = Seminar::query();
        $this->pastClasses ? $query->whereBeforeToday('date')->orderBy('date', 'desc') : $query->whereTodayOrAfter('date')->orderBy('date', 'asc');
        return $query->paginate(12);
    }

    public function togglePastClasses()
    {
        $this->pastClasses = !$this->pastClasses;
    }

};
?>

<div>
    <div class="flex justify-between items-center mb-4">
        <flux:button variant="ghost"
                     wire:click="togglePastClasses">{{$this->pastClasses ? 'Upcoming Classes' : 'Past Classes'}}</flux:button>
        <flux:button variant="primary" href="/seminars/create">Add Seminar</flux:button>
    </div>
    <flux:table :paginate="$this->seminars">
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Location</flux:table.column>
            <flux:table.column>Instructor</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column># Enrolled</flux:table.column>
            <flux:table.column>Discount</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->seminars as $seminar)
                <livewire:rows.seminar :seminar="$seminar" :key="$seminar->id" @seminar-removed="$refresh"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
