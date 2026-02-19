<?php

use App\Models\Seminar;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Student $student;
    public \App\Livewire\Forms\EnrollmentForm $form;

    public function mount()
    {
        $this->form->student_id = $this->student->id;
    }

    public function addEnrollment()
    {
        $this->form->student = $this->student;
        $this->form->store();

        $this->form->reset();
        $this->form->student_id = $this->student->id;

        Flux::toast('Enrollment added.');
        Flux::modals()->close();
    }

    public function removeEnrollment($id)
    {
        $seminar = Seminar::find($id);
        $this->student->seminars()->detach($seminar);

        Flux::toast('Enrollment removed.');
        Flux::modals()->close();
    }

    #[Computed]
    public function seminarOptions()
    {
        $query = Seminar::query()->where('date','>',today()->subMonth());
        return $query->orderBy('date','asc')->get();
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
                                <flux:modal.trigger :name="'remove-enrollment-' . $seminar->id">
                                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                                </flux:modal.trigger>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:modal :name="'remove-enrollment-' . $seminar->id" class="min-w-[22rem]">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Remove Enrollment?</flux:heading>
                                    <flux:text class="mt-2">
                                        You're about to remove this enrollment.<br>
                                        This action does not remove charges or payments associated with the enrollment.
                                    </flux:text>
                                </div>
                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">Cancel</flux:button>
                                    </flux:modal.close>
                                    <flux:button wire:click="removeEnrollment({{$seminar->id}})" variant="danger">Remove Enrollment</flux:button>
                                </div>
                            </div>
                        </flux:modal>
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
                <flux:select wire:model="form.seminar_id" label="Select a seminar">
                    <flux:select.option value="">Select a seminar...</flux:select.option>
                    @foreach ($this->seminarOptions as $seminar)
                        <flux:select.option value="{{ $seminar->id }}">
                            {{ $seminar->date->format('m/d/y') }} - {{$seminar->class_type}}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="form.level" label="Class Level">
                    <flux:select.option value="">Select a level...</flux:select.option>
                    <flux:select.option value="{{\App\Enums\StudentTypes::COURSE_A}}">{{\App\Enums\StudentTypes::COURSE_A->label()}}</flux:select.option>
                    <flux:select.option value="{{\App\Enums\StudentTypes::COURSE_B}}">{{\App\Enums\StudentTypes::COURSE_B->label()}}</flux:select.option>
                    <flux:select.option value="{{\App\Enums\StudentTypes::COURSE_C}}">{{\App\Enums\StudentTypes::COURSE_C->label()}}</flux:select.option>
                    <flux:select.option value="{{\App\Enums\StudentTypes::POINT_REDUCTION}}">{{\App\Enums\StudentTypes::POINT_REDUCTION->label()}}</flux:select.option>
                    <flux:select.option value="{{\App\Enums\StudentTypes::INSTRUCTOR_TRAINING}}">{{\App\Enums\StudentTypes::INSTRUCTOR_TRAINING->label()}}</flux:select.option>
                </flux:select>
                <flux:input wire:model="form.discount" label="Discount" type="number" />
                <flux:input wire:model="form.tuition" label="Tuition" type="number" />

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>

        </div>
    </flux:modal>
</div>
