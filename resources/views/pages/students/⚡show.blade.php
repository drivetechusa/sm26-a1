<?php

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.student')]
class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }
};
?>

<div>
    <div class="grid auto-rows-min gap-4 md:grid-cols-3 h-[325px]">
        <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="py-3 px-2 space-y-3">
                <x-status-badge color="{{$this->student->status->color()}}" label="{{$this->student->status->label()}}" />
                <p class="text-xl font-extrabold text-green-600">{{$this->student->id}} / <span class="text-base font-medium">{{$this->student->stu_web_id}}</span></p>
                <p class="text-xl font-bold">{{ $this->student->fullName }}</p>
                <address>
                    {{ $this->student->address }}<br/>
                    {{$this->student->csz}}
                </address>
                <p>
                    {{\App\Functions::formatPhone($student->student_phone)}} <br/>
                    <a href="mailto:{{$student->email_student}}">{{$student->email_student}}</a>
                </p>
                <flux:modal.trigger name="show-emergency-contacts">
                    <flux:button variant="primary">Emergency Contacts</flux:button>
                </flux:modal.trigger>
            </div>

        </div>
        <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="py-3 px-2">
                <x-status-badge color="{{$this->student->type->color()}}" label="{{$this->student->type->label()}}" />
                <div class="overflow-y-scroll bg-white dark:bg-zinc-800  shadow-sm sm:rounded-lg">
                    <div class="border-t border-gray-100">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">DOB (Age)</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->dobAge}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">Start | Complete</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->startDate}} | {{$this->student->completedDate}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">Hours</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->drive_time_completed}} / {{$this->student->drive_time_purchased}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">Permit</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->permit_number}} || {{$this->student->dateIssued}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">Eligible</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->eligibleDate}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">High School</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->high_school}}</dd>
                            </div>
                            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-12 sm:px-2">
                                <dt class="text-sm font-medium text-gray-900 dark:text-zinc-400">Instructor</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 dark:text-zinc-400 sm:col-span-2 sm:mt-0">{{$this->student->instructor->name ?? ''}}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="py-3 px-2">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <p>Balance: {{money($student->balance)}}</p>
                    <p>{{$student->username}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="py-3 px-2">
            <flux:tab.group>
                <flux:tabs>
                    <flux:tab name="notes" icon="document">Notes</flux:tab>
                    <flux:tab name="financials" icon="banknotes">Financials</flux:tab>
                    <flux:tab name="lessons" icon="calendar">Lessons</flux:tab>
                    <flux:tab name="testing" icon="shield-check">Testing</flux:tab>
                    <flux:tab name="enrollments" icon="presentation-chart-line">Enrollments</flux:tab>
                </flux:tabs>

                <flux:tab.panel name="notes"><livewire:student.notes :student="$this->student" wire:ref="student-notes-table"/></flux:tab.panel>
                <flux:tab.panel name="financials">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div><livewire:student.charges :student="$this->student" /></div>
                        <div><livewire:student.payments :student="$this->student" /></div>
                    </div>
                </flux:tab.panel>
                <flux:tab.panel name="lessons"><livewire:student.lessons :student="$this->student" /></flux:tab.panel>
                <flux:tab.panel name="testing"><livewire:student.testing :student="$this->student" /></flux:tab.panel>
                <flux:tab.panel name="enrollments"><livewire:student.enrollments :student="$this->student" /></flux:tab.panel>
            </flux:tab.group>
        </div>

    </div>
    <flux:modal name="show-emergency-contacts" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Emergency Contacts</flux:heading>
            </div>
            <div>
                <p>
                    {{$this->student->parent_name}} ({{$this->student->parent_relationship}}) <br/>
                    {{\App\Functions::formatPhone($this->student->phone)}} <br/>
                    <a href="mailto:{{$this->student->email}}">{{$this->student->email}}</a>

                </p>
            </div>
            <div>
                <p>
                    {{$this->student->parent_name_alternate}} ({{$this->student->parent_alternate_relationship}})<br/>
                    {{\App\Functions::formatPhone($this->student->secondary_phone)}} <br/>
                    <a href="mailto:{{$this->student->guardian_2_email}}">{{$this->student->guardian_2_email}}</a>
                </p>
            </div>

        </div>
    </flux:modal>
</div>
