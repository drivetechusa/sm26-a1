<?php

use App\Livewire\Forms\ClassroomForm;
use App\Models\Classroom;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component
{
    public Classroom $classroom;

    public ClassroomForm $form;

    public function mount()
    {
        $this->classroom = new Classroom();
        $this->classroom->active = true;
    }

    public function save()
    {
        $classroom = $this->form->store();

        return $this->redirect(route('classrooms.index'), navigate: true);
    }
};
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <flux:heading size="xl">Create New Classroom</flux:heading>
        <flux:subheading>Enter classroom information to create a new classroom record.</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-8">
        {{-- Basic Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Basic Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <flux:field>
                    <flux:label required>Name</flux:label>
                    <flux:input wire:model="form.name" placeholder="Classroom name" />
                    <flux:error name="form.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Capacity</flux:label>
                    <flux:input type="number" wire:model="form.Capacity" placeholder="0" />
                    <flux:error name="form.Capacity" />
                </flux:field>

                <flux:field>
                    <flux:label>Active</flux:label>
                    <flux:checkbox wire:model="form.active" label="Classroom is active" />
                    <flux:error name="form.active" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Address Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Address Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <flux:field>
                    <flux:label required>Street Address</flux:label>
                    <flux:input wire:model="form.street" placeholder="123 Main St" />
                    <flux:error name="form.street" />
                </flux:field>

                <flux:field>
                    <flux:label>Street Address 2</flux:label>
                    <flux:input wire:model="form.street1" placeholder="Apt, Suite, Unit, etc." />
                    <flux:error name="form.street1" />
                </flux:field>

                <flux:field>
                    <flux:label required>Zipcode</flux:label>
                    <flux:input wire:model="form.zip_id" placeholder="29445" mask="99999" />
                    <flux:error name="form.zip_id" />
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label>Directions</flux:label>
                    <flux:textarea wire:model="form.directions" placeholder="Directions to the classroom..." rows="3" />
                    <flux:error name="form.directions" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Course Pricing Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Course Pricing</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <flux:field>
                    <flux:label>Teen Course Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.teen_price" placeholder="0.00" />
                    <flux:error name="form.teen_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Extended Course Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.ext_price" placeholder="0.00" />
                    <flux:error name="form.ext_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Advanced Course Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.adv_price" placeholder="0.00" />
                    <flux:error name="form.adv_price" />
                </flux:field>

                <flux:field>
                    <flux:label>LXL Course Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.lxl_price" placeholder="0.00" />
                    <flux:error name="form.lxl_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Point Reduction Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.point_price" placeholder="0.00" />
                    <flux:error name="form.point_price" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Service Pricing Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Service Pricing</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <flux:field>
                    <flux:label>Permit Test Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.permit_test_price" placeholder="0.00" />
                    <flux:error name="form.permit_test_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Road Test Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.road_test_price" placeholder="0.00" />
                    <flux:error name="form.road_test_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Evaluation Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.evaluation_price" placeholder="0.00" />
                    <flux:error name="form.evaluation_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Instructor Course Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.instructor_course_price" placeholder="0.00" />
                    <flux:error name="form.instructor_course_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Hand Controls Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.hand_controls_price" placeholder="0.00" />
                    <flux:error name="form.hand_controls_price" />
                </flux:field>

                <flux:field>
                    <flux:label>Insurance Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.insurance_price" placeholder="0.00" />
                    <flux:error name="form.insurance_price" />
                </flux:field>

                <flux:field>
                    <flux:label>LXL Discount Price</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.lxl_discount_price" placeholder="0.00" />
                    <flux:error name="form.lxl_discount_price" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Fees Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Fees</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <flux:field>
                    <flux:label>Registration Fee</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.registrationfee" placeholder="0.00" />
                    <flux:error name="form.registrationfee" />
                </flux:field>

                <flux:field>
                    <flux:label>DMV Fee</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.dmvfee" placeholder="0.00" />
                    <flux:error name="form.dmvfee" />
                </flux:field>

                <flux:field>
                    <flux:label>Late Cancel Fee</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.latecancelfee" placeholder="0.00" />
                    <flux:error name="form.latecancelfee" />
                </flux:field>

                <flux:field>
                    <flux:label>No Show Fee</flux:label>
                    <flux:input type="number" step="0.01" wire:model="form.noshowfee" placeholder="0.00" />
                    <flux:error name="form.noshowfee" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" href="{{ route('classrooms.index') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Create Classroom
            </flux:button>
        </div>
    </form>
</div>