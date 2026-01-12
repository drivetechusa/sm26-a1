<?php

use App\Models\Student;
use Livewire\Component;
use App\Livewire\Forms\TestingForm;

new class extends Component {
    public TestingForm $form;

    public Student $student;

    public function mount(): void
    {
        $this->form->student_id = $this->student->id;
    }

    public function save(): void
    {
        $this->form->store();

        $this->modal('add-test')->close();

        $this->dispatch('test-created');
    }
};
?>

<div>
    <flux:modal.trigger name="add-test">
        <flux:button>Add Test</flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-test" flyout>
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Add Test</flux:heading>
                <flux:text class="mt-2">Create a new test record for this student.</flux:text>
            </div>

            <flux:field>
                <flux:label>Date *</flux:label>
                <flux:input wire:model="form.date" type="date" required/>
                <flux:error name="form.date"/>
            </flux:field>

            <flux:select wire:model="form.test_type" label="Test Type *">
                <flux:select.option>Select test type...</flux:select.option>
                <flux:select.option value="Knowledge">Knowledge</flux:select.option>
                <flux:select.option value="Skills">Skills</flux:select.option>
            </flux:select>

            <flux:field>
                <flux:label>Route *</flux:label>
                <flux:input wire:model="form.route" placeholder="Enter route"/>
                <flux:error name="form.route"/>
            </flux:field>

            <flux:select wire:model="form.status" label="Status *">
                <flux:select.option>Select status...</flux:select.option>
                <flux:select.option value="Scheduled">Scheduled</flux:select.option>
                <flux:select.option value="Cancelled">Cancelled</flux:select.option>
                <flux:select.option value="Pass">Pass</flux:select.option>
                <flux:select.option value="Fail">Fail</flux:select.option>
            </flux:select>

            <flux:field>
                <flux:label>Receipt #</flux:label>
                <flux:input wire:model="form.so_id" type="number" placeholder="Enter Receipt #"/>
                <flux:error name="form.so_id"/>
            </flux:field>

            <flux:field>
                <flux:label>Test ID</flux:label>
                <flux:input wire:model="form.test_id" type="number" placeholder="Enter Test ID"/>
                <flux:error name="form.test_id"/>
            </flux:field>

            <div class="space-y-3">
                <flux:checkbox wire:model.boolean="form.walk_in" label="Walk In"/>
                <flux:checkbox wire:model.boolean="form.substitute" label="Substitute"/>
                <flux:checkbox wire:model.boolean="form.complete" label="Complete"/>
            </div>

            <div class="flex">
                <flux:spacer/>

                <flux:button type="submit" variant="primary">Save Test</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
