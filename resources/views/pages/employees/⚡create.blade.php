<?php

use App\Livewire\Forms\EmployeeForm;
use App\Models\Employee;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component
{
    public Employee $employee;

    public EmployeeForm $form;

    public function mount()
    {
        $this->employee = new Employee();
        $this->employee->active = true;
    }

    public function save()
    {
        $employee = $this->form->store();

        return $this->redirect(route('employees.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'vehicles' => Vehicle::where('active', true)->orderBy('name')->get(),
        ];
    }
};
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <flux:heading size="xl">Create New Employee</flux:heading>
        <flux:subheading>Enter employee information to create a new employee record.</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-8">
        {{-- Basic Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Basic Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <flux:field>
                    <flux:label required>First Name</flux:label>
                    <flux:input wire:model="form.firstname" placeholder="First name" />
                    <flux:error name="form.firstname" />
                </flux:field>

                <flux:field>
                    <flux:label>Middle Name</flux:label>
                    <flux:input wire:model="form.middlename" placeholder="Middle name" />
                    <flux:error name="form.middlename" />
                </flux:field>

                <flux:field>
                    <flux:label required>Last Name</flux:label>
                    <flux:input wire:model="form.lastname" placeholder="Last name" />
                    <flux:error name="form.lastname" />
                </flux:field>

                <flux:field>
                    <flux:label>Suffix</flux:label>
                    <flux:input wire:model="form.namesuffix" placeholder="Jr., Sr., III, etc." />
                    <flux:error name="form.namesuffix" />
                </flux:field>

                <flux:field>
                    <flux:label>Date of Birth</flux:label>
                    <flux:input type="date" wire:model="form.dob" />
                    <flux:error name="form.dob" />
                </flux:field>

            </div>
        </flux:card>

        {{-- Contact Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Contact Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <flux:field>
                    <flux:label>Street Address</flux:label>
                    <flux:input wire:model="form.street" placeholder="123 Main St" />
                    <flux:error name="form.street" />
                </flux:field>

                <flux:field>
                    <flux:label>Street Address 2</flux:label>
                    <flux:input wire:model="form.street1" placeholder="Apt, Suite, Unit, etc." />
                    <flux:error name="form.street1" />
                </flux:field>

                <flux:field>
                    <flux:label>Zipcode</flux:label>
                    <flux:input wire:model="form.zip_id" placeholder="29445" mask="99999" />
                    <flux:error name="form.zip_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Phone</flux:label>
                    <flux:input wire:model="form.phone" type="tel" mask="9999999999" />
                    <flux:error name="form.phone" />
                </flux:field>

                <flux:field>
                    <flux:label>Secondary Phone</flux:label>
                    <flux:input wire:model="form.secondary_phone" type="tel" mask="9999999999" />
                    <flux:error name="form.secondary_phone" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input wire:model="form.email" type="email" placeholder="email@example.com" />
                    <flux:error name="form.email" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Employment Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Employment Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <flux:field>
                    <flux:label>Hire Date</flux:label>
                    <flux:input type="date" wire:model="form.hire_date" />
                    <flux:error name="form.hire_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Termination Date</flux:label>
                    <flux:input type="date" wire:model="form.term_date" />
                    <flux:error name="form.term_date" />
                </flux:field>

                <flux:field>
                    <flux:label>User Level</flux:label>
                    <flux:select wire:model="form.user_level" placeholder="Select user level...">
                        <flux:select.option value="">Select user level...</flux:select.option>
                        <flux:select.option value="admin">Admin</flux:select.option>
                        <flux:select.option value="manager">Manager</flux:select.option>
                        <flux:select.option value="instructor">Instructor</flux:select.option>
                        <flux:select.option value="staff">Staff</flux:select.option>
                    </flux:select>
                    <flux:error name="form.user_level" />
                </flux:field>

                <flux:field>
                    <flux:label>Username</flux:label>
                    <flux:input wire:model="form.username" placeholder="Username" />
                    <flux:error name="form.username" />
                </flux:field>

                <flux:field>
                    <flux:label>Active</flux:label>
                    <flux:checkbox wire:model="form.active" label="Employee is active" />
                    <flux:error name="form.active" />
                </flux:field>
            </div>
        </flux:card>

        {{-- License Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">License Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <flux:field>
                    <flux:label>Driver's License</flux:label>
                    <flux:input wire:model="form.dl_license" placeholder="License number" />
                    <flux:error name="form.dl_license" />
                </flux:field>

                <flux:field>
                    <flux:label>DL Expiration Date</flux:label>
                    <flux:input type="date" wire:model="form.dl_expire" />
                    <flux:error name="form.dl_expire" />
                </flux:field>

                <flux:field>
                    <flux:label>Instructor License</flux:label>
                    <flux:input wire:model="form.inst_license" placeholder="Instructor license number" />
                    <flux:error name="form.inst_license" />
                </flux:field>

                <flux:field>
                    <flux:label>CDTP Instructor Number</flux:label>
                    <flux:input wire:model="form.cdtp_instructor_number" placeholder="CDTP number" />
                    <flux:error name="form.cdtp_instructor_number" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Vehicle Assignment Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Vehicle Assignment</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <flux:field>
                    <flux:label>Assigned Vehicle</flux:label>
                    <flux:select wire:model="form.vehicle_id" placeholder="Select vehicle...">
                        <flux:select.option value="">Select vehicle...</flux:select.option>
                        @foreach($vehicles as $vehicle)
                            <flux:select.option value="{{ $vehicle->id }}">{{ $vehicle->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.vehicle_id" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" href="{{ route('employees.index') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Create Employee
            </flux:button>
        </div>
    </form>
</div>
