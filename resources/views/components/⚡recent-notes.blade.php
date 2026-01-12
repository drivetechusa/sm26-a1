<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination, \App\Traits\SearchableTable;

    #[Computed]
    public function notes()
    {
        $start = clone(today())->endOfDay();
        $end = clone(today())->subDays(3)->startOfDay();
        $query = Note::query();
        $query->whereBetween('last_update', [$end, $start]);
        $query->orderBy('last_update', 'desc');

        return $query->paginate(5);
    }
};
?>

<div class="py-2 px-3">
    <h1>Recent Notes</h1>
    <flux:table :paginate="$this->notes">
        <flux:table.columns>
            <flux:table.column>Student</flux:table.column>
            <flux:table.column>Created</flux:table.column>
            <flux:table.column>Note</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->notes as $note)
                <livewire:rows.note :note="$note" key="{{$note->id}}"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
