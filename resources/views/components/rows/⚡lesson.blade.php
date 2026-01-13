<?php

use App\Models\Employee;
use App\Models\Lesson;
use App\Livewire\Forms\LessonForm;
use App\Models\Vehicle;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Lesson $lesson;
    public LessonForm $form;

    public function mount()
    {
        $this->form->setLesson($this->lesson);
    }

    public function editLesson()
    {
        $this->form->update();

        $this->form->reset();
        Flux::toast('Lesson updated.');
        Flux::modals()->close();
    }

    #[Computed]
    public function instructors()
    {
        return Employee::whereNotNull('inst_license')->where('active', true)->orderBy('lastname')->get();
    }

    #[Computed]
    public function vehicles()
    {
        return Vehicle::where('active', true)->get();
    }

    public function deleteLesson()
    {
        $this->lesson->delete();
        Flux::modals()->close();
        Flux::toast('Lesson removed');
        $this->dispatch('lesson-deleted');
    }
};
?>

<flux:table.row>
    <flux:table.cell>{{$lesson->start_time->format('m/d/y')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->type}}</flux:table.cell>
    <flux:table.cell>{{$lesson->start_time->format('H:ia')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_time->format('H:ia')}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_time->diffInHours($lesson->start_time, true)}}</flux:table.cell>
    <flux:table.cell>{{$lesson->begin_mileage}}</flux:table.cell>
    <flux:table.cell>{{$lesson->end_mileage}}</flux:table.cell>
    <flux:table.cell>{{ $lesson->end_mileage - $lesson->begin_mileage }}</flux:table.cell>
    <flux:table.cell>{{$lesson->instructor->name}}</flux:table.cell>
    <flux:table.cell>
        <flux:dropdown align="end">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>

            <flux:menu>
                <flux:modal.trigger :name="'edit-lesson-' . $lesson->id">
                    <flux:menu.item icon="pencil-square">Edit Lesson</flux:menu.item>
                </flux:modal.trigger>
                <flux:modal.trigger name="delete-lesson">
                    <flux:menu.item icon="no-symbol">Delete</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>


        <flux:modal :name="'edit-lesson-' . $lesson->id" class="md:w-2/3">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Lesson</flux:heading>
                    {{--                    <flux:text class="mt-2">Edit lesson for {{ $lesson->student->firstname }} {{ $lesson->student->lastname }}.</flux:text>--}}
                </div>
                <form wire:submit="editLesson" class="space-y-6">
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
                                    <flux:select.option value="{{ $instructor->id }}">{{ $instructor->lastname }}
                                        , {{ $instructor->firstname }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="form.employee_id"/>
                        </flux:field>
                        <flux:field>
                            <flux:label>Vehicle</flux:label>
                            <flux:select wire:model="form.vehicle_id" placeholder="Select vehicle...">
                                <flux:select.option value="">None</flux:select.option>
                                @foreach ($this->vehicles as $vehicle)
                                    <flux:select.option
                                        value="{{ $vehicle->id }}">{{ $vehicle->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="form.vehicle_id"/>
                        </flux:field>
                        <flux:input type="date" wire:model="form.date" label="Date *"/>

                        <flux:field>
                            <flux:label required>Start Time</flux:label>
                            <flux:input type="time" wire:model="form.start_time" mask="99:99"/>
                            <flux:error name="form.start_time"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>End Time</flux:label>
                            <flux:input type="time" wire:model="form.end_time" mask="99:99"/>
                            <flux:error name="form.end_time"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>Begin Mileage</flux:label>
                            <flux:input type="number" step="0.1" wire:model="form.begin_mileage"
                                        placeholder="Starting mileage"/>
                            <flux:error name="form.begin_mileage"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>End Mileage</flux:label>
                            <flux:input type="number" step="0.1" wire:model="form.end_mileage"
                                        placeholder="Ending mileage"/>
                            <flux:error name="form.end_mileage"/>
                        </flux:field>
                    </div>

                    <div class="flex">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">Update Lesson</flux:button>
                    </div>
                </form>

            </div>
        </flux:modal>
        <flux:modal name="delete-lesson" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete lesson?</flux:heading>
                    <flux:text class="mt-2">
                        You're about to delete this lesson.<br>
                        This action cannot be reversed.
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="deleteLesson" variant="danger">Delete lesson</flux:button>
                </div>
            </div>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
