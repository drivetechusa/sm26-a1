<?php

use App\Livewire\Forms\LessonForm;
use App\Models\Employee;
use App\Models\Lesson;
use App\Models\PickupLocation;
use App\Models\Student;
use App\Models\Vehicle;
use App\Models\Zone;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    public Student $student;

    public LessonForm $form;

    public function mount()
    {
        $this->form->student_id = $this->student->id;
    }

    public function addLesson()
    {
        $this->form->student_id = $this->student->id;
        $this->form->store();

        $this->form->reset();
        $this->form->student_id = $this->student->id;

        Flux::toast('Lesson added.');
        Flux::modals()->close();
    }

    #[Computed]
    public function lessons()
    {
        $query = Lesson::query()->where('student_id', $this->student->id);

        return $query->get();
    }

    #[Computed]
    public function scheduledLessons()
    {
        $query = \App\Models\Scheduler\Lesson::query()->where('student_id', $this->student->stu_web_id)->where('complete', false);

        return $query->orderBy('start_time', 'asc')->get();
    }

    #[Computed]
    public function instructors()
    {
        return Employee::whereNotNull('inst_license')->where('active', true)->orderBy('lastname')->get();
    }

    #[Computed]
    public function zones()
    {
        return Zone::where('archived', false)->orderBy('name')->get();
    }



    #[Computed]
    public function vehicles()
    {
        return Vehicle::where('active', true)->get();
    }
};
?>

<div>
    <div>
        <flux:modal.trigger name="add-lesson">
            <flux:button size="sm" variant="filled">Add Lesson</flux:button>
        </flux:modal.trigger>

        <flux:modal name="add-lesson" class="md:w-2/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add Lesson</flux:heading>
                    <flux:text class="mt-2">Create a new lesson for {{ $student->firstname }} {{ $student->lastname }}.</flux:text>
                </div>
                <form wire:submit="addLesson" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:select wire:model="form.type" placeholder="Select lesson type..." label="Lesson Type *">
                            <flux:select.option value="">Select lesson type...</flux:select.option>
                            <flux:select.option>In-Car</flux:select.option>
                            <flux:select.option>CR Complete</flux:select.option>
                            <flux:select.option>Road Test</flux:select.option>
                            <flux:select.option>Knowledge Test</flux:select.option>
                            <flux:select.option>Classroom</flux:select.option>
                            <flux:select.option>SNS</flux:select.option>
                        </flux:select>

                        <flux:field>
                            <flux:label required>Instructor *</flux:label>
                            <flux:select wire:model="form.employee_id" placeholder="Select instructor...">
                                <flux:select.option value="">Select instructor...</flux:select.option>
                                @foreach ($this->instructors as $instructor)
                                    <flux:select.option value="{{ $instructor->id }}">{{ $instructor->lastname }}, {{ $instructor->firstname }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="form.employee_id" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Vehicle</flux:label>
                            <flux:select wire:model="form.vehicle_id" placeholder="Select vehicle...">
                                <flux:select.option value="">None</flux:select.option>
                                @foreach ($this->vehicles as $vehicle)
                                    <flux:select.option value="{{ $vehicle->id }}">{{ $vehicle->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="form.vehicle_id" />
                        </flux:field>
                        <flux:input type="date" wire:model="form.date" label="Date *" />

                        <flux:field>
                            <flux:label required>Start Time</flux:label>
                            <flux:input type="time" wire:model="form.start_time" mask="99:99"/>
                            <flux:error name="form.start_time" />
                        </flux:field>

                        <flux:field>
                            <flux:label>End Time</flux:label>
                            <flux:input type="time" wire:model="form.end_time" mask="99:99"/>
                            <flux:error name="form.end_time" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Begin Mileage</flux:label>
                            <flux:input type="number" step="0.1" wire:model="form.begin_mileage" placeholder="Starting mileage" />
                            <flux:error name="form.begin_mileage" />
                        </flux:field>

                        <flux:field>
                            <flux:label>End Mileage</flux:label>
                            <flux:input type="number" step="0.1" wire:model="form.end_mileage" placeholder="Ending mileage" />
                            <flux:error name="form.end_mileage" />
                        </flux:field>
                    </div>

                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="submit" variant="primary">Create Lesson</flux:button>
                    </div>
                </form>

            </div>
        </flux:modal>
    </div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Start</flux:table.column>
            <flux:table.column>End</flux:table.column>
            <flux:table.column>Total Hrs</flux:table.column>
            <flux:table.column>Start Miles</flux:table.column>
            <flux:table.column>End Miles</flux:table.column>
            <flux:table.column>Total Miles</flux:table.column>
            <flux:table.column>Instructor</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->scheduledLessons as $scheduledLesson)
                <livewire:rows.scheduled-lessons :lesson="$scheduledLesson" :key="$scheduledLesson->id"/>
            @endforeach
            @foreach ($this->lessons as $lesson)
                <livewire:rows.lesson :lesson="$lesson" :key="$lesson->id" @lesson-deleted="$refresh"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
