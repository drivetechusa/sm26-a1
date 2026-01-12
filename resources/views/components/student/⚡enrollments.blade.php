<?php

use App\Models\Seminar;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function addEnrollment()
    {

    }

    #[Computed]
    public function seminarOptions()
    {
        $query = Seminar::query()->where('date','>',today()->subMonth());
        return $query->get();
    }

};
?>

<div>
    <div class="flex justify-between items-center mb-4">
        <flux:modal.trigger name="add-enrollment">
            <flux:button size="sm">Add Enrollment</flux:button>
        </flux:modal.trigger>
    </div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Level</flux:table.column>
            <flux:table.column>Instructor</flux:table.column>
            <flux:table.column>Classroom</flux:table.column>
            <flux:table.column>Tuition</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->student->seminars as $seminar)
                <flux:table.row>
                    <flux:table.cell>{{ $seminar->date->format('m/d/y') }}</flux:table.cell>
                    <flux:table.cell>{{ $seminar->class_type }}</flux:table.cell>
                    <flux:table.cell>{{ $seminar->pivot->level }}</flux:table.cell>
                    <flux:table.cell>{{ $seminar->instructor->name }}</flux:table.cell>
                    <flux:table.cell>{{ $seminar->classroom->name }}</flux:table.cell>
                    <flux:table.cell>{{ $seminar->pivot->tuition }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

                            <flux:menu>
                                <flux:menu.item href="/documents/print_contract/{{$student->id}}/{{$seminar->id}}"
                                                icon="printer" target="_blank">Print Contract
                                </flux:menu.item>
                                <livewire:emails.enrollment_packet :student="$student" :seminar="$seminar"/>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    <flux:modal variant="flyout" name="add-enrollment" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Enrollment</flux:heading>
            </div>
            <form wire:submit="addEnrollment" class="space-y-4">
                <flux:select wire:model="form.seminar_id" label="Select a seminar" placeholder="Select a seminar...">
                    <flxu:select.option>Select a seminar...</flxu:select.option>
                    @foreach ($this->seminarOptions as $seminar)
                        <flux:select.option value="{{ $seminar->id }}">
                            {{ $seminar->date->format('m/d/y') }} - {{$seminar->class_type}}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>

        </div>
    </flux:modal>
</div>
