<?php

use App\Models\Classroom;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public $active = true;

    #[Computed]
    public function classrooms()
    {
        $query = Classroom::query();
        $query->where('active', $this->active);
        return $query->paginate(13);
    }

    public function toggleActive()
    {
        $this->active = !$this->active;
    }
};
?>

<div class="space-y-4">

    <p>{{$this->active ? 'Active' : 'Archived'}} Classrooms List</p>
    <div class="flex gap-2">
        <flux:button wire:click="toggleActive" variant="primary">{{$this->active ? 'Archived' : 'Active'}} Classrooms</flux:button>
    </div>
    <div class="w-full">
        <flux:table :paginate="$this->classrooms">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Teen Price</flux:table.column>
                <flux:table.column>Ext Price</flux:table.column>
                <flux:table.column>Adv Price</flux:table.column>
                <flux:table.column>Point Price</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->classrooms as $classroom)
                    <livewire:rows.classroom :classroom="$classroom" key="{{$classroom->id}}"/>

                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
