<?php

use App\Livewire\Forms\SeminarForm;
use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Seminar;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component
{
    public Seminar $seminar;

    public SeminarForm $form;

    public function mount($id)
    {
        $this->seminar = Seminar::findOrFail($id);
        $this->form->setSeminar($this->seminar);
    }

    public function update()
    {
        $this->form->update();

        session()->flash('success', 'Seminar updated successfully.');

        return $this->redirect(route('seminars.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'classrooms' => Classroom::where('active',true)->orderBy('name')->get(),
            'instructors' => Employee::whereNotNull('inst_license')->where('active', true)->orderBy('lastname')->get(),
        ];
    }
};
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <flux:heading size="xl">Edit Seminar</flux:heading>
        <flux:subheading>Update seminar information below.</flux:subheading>
    </div>

    <form wire:submit="update" class="space-y-8">
        {{-- Basic Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Seminar Details</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label required>Classroom</flux:label>
                    <flux:select wire:model="form.classroom_id" placeholder="Select classroom...">
                        <flux:select.option>Select classroom...</flux:select.option>
                        @foreach($classrooms as $classroom)
                            <flux:select.option value="{{ $classroom->id }}">{{ $classroom->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.classroom_id" />
                </flux:field>

                <flux:field>
                    <flux:label required>Instructor</flux:label>
                    <flux:select wire:model="form.employee_id" placeholder="Select instructor...">
                        <flux:select.option>Select instructor...</flux:select.option>
                        @foreach($instructors as $instructor)
                            <flux:select.option value="{{ $instructor->id }}">{{ $instructor->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.employee_id" />
                </flux:field>

                <flux:select wire:model="form.class_type" label="Class Type">
                    <flux:select.option>Select class type...</flux:select.option>
                    <flux:select.option value="Beginner">Beginner</flux:select.option>
                    <flux:select.option value="Point Reduction">Point Reduction</flux:select.option>
                    <flux:select.option value="Instructor Certification">Instructor Certification</flux:select.option>
                </flux:select>

            </div>
        </flux:card>

        {{-- Schedule Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Schedule Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label required>Date</flux:label>
                    <flux:input type="date" wire:model="form.date" />
                    <flux:error name="form.date" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Status & Pricing Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Status & Pricing</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label>Sale Disount</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.sale_price" placeholder="0.00" />
                    <flux:error name="form.sale_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Class Full</flux:label>
                    <flux:checkbox wire:model="form.full" label="Mark as full" />
                    <flux:error name="form.full" />
                </flux:field>

                <flux:field>
                    <flux:label>Cancelled</flux:label>
                    <flux:checkbox wire:model="form.cancelled" label="Mark as cancelled" />
                    <flux:error name="form.cancelled" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Additional Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Additional Information</flux:heading>
            </div>

            <flux:field>
                <flux:label>Additional Info</flux:label>
                <flux:textarea wire:model="form.add_info" placeholder="Enter any additional notes or information..." rows="4" />
                <flux:error name="form.add_info" />
            </flux:field>
        </flux:card>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" href="{{ route('dashboard') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Update Seminar
            </flux:button>
        </div>
    </form>
</div>
