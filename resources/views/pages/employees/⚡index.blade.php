<?php

use App\Models\Employee;
use App\Traits\SearchableTable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Employee List')]
class extends Component {
    use WithPagination, SearchableTable;

    public $active = 1;

    public function mount()
    {
        $this->searchFields = ['firstname', 'lastname', 'phone', 'email'];
    }

    #[Computed]
    public function employees()
    {
        $query = Employee::query();
        $query->where('active', $this->active);
        $query = $this->applySearch($query);
        return $query->paginate($this->perPage);
    }

    public function toggleActive()
    {
        $this->active == 1 ? $this->active = 0 : $this->active = 1;
    }
};
?>

<div class="space-y-4">
    <p>{{$this->active ? 'Active' : 'Archived'}} Employees List</p>
    <div class="flex gap-2">
        <flux:input wire:model.live.debounce.2s="search" placeholder="Search" icon="magnifying-glass"/>

        <flux:button wire:click="toggleActive" variant="primary">{{$this->active ? 'Archived' : 'Active'}}</flux:button>
        <flux:button href="{{ route('employees.create') }}" variant="primary">New Employee</flux:button>
    </div>
    <div>
        <flux:table :paginate="$this->employees" >
            <flux:table.columns>
                <flux:table.column>ID</flux:table.column>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Inst ID</flux:table.column>
                <flux:table.column>Active</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->employees as $employee)
                    <livewire:rows.employee :employee="$employee" :key="$employee->id"/>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
