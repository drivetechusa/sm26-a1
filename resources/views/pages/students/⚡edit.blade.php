<?php

use App\Enums\StudentStatus;
use App\Enums\StudentTypes;
use App\Livewire\Forms\StudentForm;
use App\Models\Employee;
use App\Models\PickupLocation;
use App\Models\School;
use App\Models\Student;
use App\Models\Zipcode;
use App\Models\Zone;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component
{
    public Student $student;

    public StudentForm $form;

    public function mount($id)
    {
        $this->student = Student::findOrFail($id);
        $this->form->setStudent($this->student);
    }

    public function update()
    {
        $this->form->update();

        session()->flash('success', 'Student updated successfully.');

        return $this->redirect(route('students.show', $this->student->id), navigate: true);
    }

    public function with(): array
    {
        return [
            'zones' => Zone::where('archived', false)->orderBy('name')->get(),
            'pickupLocations' => PickupLocation::orderBy('name')->get(),
            'instructors' => Employee::whereNotNull('inst_license')->where('active', true)->orderBy('lastname')->get(),
            'statuses' => StudentStatus::cases(),
            'types' => StudentTypes::cases(),
        ];
    }
};
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <flux:heading size="xl">Edit Student: {{ $student->fullName }}</flux:heading>
        <flux:subheading>Update student information below.</flux:subheading>
    </div>

    <<form wire:submit="update" class="space-y-8">
        {{-- Basic Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Basic Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">

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
                    <flux:input wire:model="form.suffix" placeholder="Jr., Sr., III, etc." />
                    <flux:error name="form.suffix" />
                </flux:field>

                <flux:field>
                    <flux:label>Goes By</flux:label>
                    <flux:input wire:model="form.goes_by" placeholder="Preferred name" />
                    <flux:error name="form.goes_by" />
                </flux:field>

                <flux:field>
                    <flux:label>Date of Birth</flux:label>
                    <flux:input type="date" wire:model="form.dob" />
                    <flux:error name="form.dob" />
                </flux:field>

                <flux:field>
                    <flux:label>Gender</flux:label>
                    <flux:select wire:model="form.gender" placeholder="Select gender...">
                        <flux:select.option>Select gender...</flux:select.option>
                        <flux:select.option value="male">Male</flux:select.option>
                        <flux:select.option value="female">Female</flux:select.option>
                        <flux:select.option value="other">Other</flux:select.option>
                        <flux:select.option value="prefer not to say">Prefer Not to Say</flux:select.option>
                    </flux:select>
                    <flux:error name="form.gender" />
                </flux:field>
                <flux:field>
                    <flux:label>SSN</flux:label>
                    <flux:input wire:model="form.ssn" placeholder="XXXX" mask="9999"/>
                    <flux:error name="form.ssn" />
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
                    <flux:input wire:model="form.zipcode" placeholder="29445" mask="99999" />
                    <flux:error name="form.zip_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Neighborhood</flux:label>
                    <flux:input wire:model="form.neighborhood" placeholder="Neighborhood" />
                    <flux:error name="form.neighborhood" />
                </flux:field>
                <flux:field>
                    <flux:label>Student Phone</flux:label>
                    <flux:input wire:model="form.student_phone" type="tel" placeholder="(555) 555-5555" />
                    <flux:error name="form.student_phone" />
                </flux:field>
                <flux:field>
                    <flux:label>Student Email</flux:label>
                    <flux:input wire:model="form.email_student" type="email" placeholder="student@example.com" />
                    <flux:error name="form.email_student" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Parent/Guardian Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Parent/Guardian Information</flux:heading>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <flux:field>
                        <flux:label>Parent/Guardian Name</flux:label>
                        <flux:input wire:model="form.parent_name" placeholder="Full name" />
                        <flux:error name="form.parent_name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Phone</flux:label>
                        <flux:input wire:model="form.phone" type="tel" placeholder="(555) 555-5555" />
                        <flux:error name="form.phone" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input wire:model="form.email" type="email" placeholder="email@example.com" />
                        <flux:error name="form.email" />
                    </flux:field>
                    <flux:select
                        label="Relationship to Student"
                        wire:model="form.parent_relationship"
                        placeholder="Select relationship"
                    >
                        <flux:select.option value="">Select relationship</flux:select.option>
                        <flux:select.option value="mother">Mother</flux:select.option>
                        <flux:select.option value="father">Father</flux:select.option>
                        <flux:select.option value="spouse">Spouse</flux:select.option>
                        <flux:select.option value="stepmother">Stepmother</flux:select.option>
                        <flux:select.option value="stepfather">Stepfather</flux:select.option>
                        <flux:select.option value="grandparent">Grandparent</flux:select.option>
                        <flux:select.option value="guardian">Legal Guardian</flux:select.option>
                        <flux:select.option value="case_worker">Case Worker</flux:select.option>
                        <flux:select.option value="other">Other</flux:select.option>
                    </flux:select>
                </div>

                <flux:separator />

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <flux:field>
                        <flux:label>Parent/Guardian Name</flux:label>
                        <flux:input wire:model="form.parent_name_alternate" placeholder="Full name" />
                        <flux:error name="form.parent_name_alternate" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Phone</flux:label>
                        <flux:input wire:model="form.secondary_phone" type="tel" placeholder="(555) 555-5555" />
                        <flux:error name="form.secondary_phone" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input wire:model="form.guardian_2_email" type="email" placeholder="email@example.com" />
                        <flux:error name="form.guardian_2_email" />
                    </flux:field>
                    <flux:select
                        label="Relationship to Student"
                        wire:model="form.parent_alternate_relationship"
                        placeholder="Select relationship"
                    >
                        <flux:select.option value="">Select relationship</flux:select.option>
                        <flux:select.option value="mother">Mother</flux:select.option>
                        <flux:select.option value="father">Father</flux:select.option>
                        <flux:select.option value="spouse">Spouse</flux:select.option>
                        <flux:select.option value="stepmother">Stepmother</flux:select.option>
                        <flux:select.option value="stepfather">Stepfather</flux:select.option>
                        <flux:select.option value="grandparent">Grandparent</flux:select.option>
                        <flux:select.option value="guardian">Legal Guardian</flux:select.option>
                        <flux:select.option value="case_worker">Case Worker</flux:select.option>
                        <flux:select.option value="other">Other</flux:select.option>
                    </flux:select>


                </div>
            </div>
        </flux:card>

        {{-- Status & Enrollment Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Status & Enrollment Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="form.status" placeholder="Select status...">
                        <flux:select.option>Select status...</flux:select.option>
                        @foreach($statuses as $status)
                            <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.status" />
                </flux:field>

                <flux:field>
                    <flux:label>Type</flux:label>
                    <flux:select wire:model="form.type" placeholder="Select type...">
                        <flux:select.option>Select type...</flux:select.option>
                        @foreach($types as $type)
                            <flux:select.option value="{{ $type->value }}">{{ $type->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.type" />
                </flux:field>

                <flux:field>
                    <flux:label>Date Started</flux:label>
                    <flux:input type="date" wire:model="form.date_started" />
                    <flux:error name="form.date_started" />
                </flux:field>

                <flux:field>
                    <flux:label>Date Completed</flux:label>
                    <flux:input type="date" wire:model="form.date_completed" />
                    <flux:error name="form.date_completed" />
                </flux:field>

                <flux:field>
                    <flux:label>High School</flux:label>
                    <flux:input wire:model="form.high_school" placeholder="High school name" />
                    <flux:error name="form.high_school" />
                </flux:field>

                <flux:field>
                    <flux:label>Instructor</flux:label>
                    <flux:select wire:model="form.instructor_id" placeholder="Select instructor...">
                        <flux:select.option>Select instructor...</flux:select.option>
                        @foreach($instructors as $instructor)
                            <flux:select.option value="{{ $instructor->id }}">{{ $instructor->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.instructor_id" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Permit Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Permit Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <flux:field>
                    <flux:label>Permit Number</flux:label>
                    <flux:input wire:model="form.permit_number" placeholder="Permit number" />
                    <flux:error name="form.permit_number" />
                </flux:field>

                <flux:field>
                    <flux:label>Issue Date</flux:label>
                    <flux:input type="date" wire:model="form.issue_date" />
                    <flux:error name="form.issue_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Renewal Date</flux:label>
                    <flux:input type="date" wire:model="form.renewal_date" />
                    <flux:error name="form.renewal_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Permit Verified</flux:label>
                    <flux:checkbox wire:model="form.permit_verified" label="Verified" />
                    <flux:error name="form.permit_verified" />
                </flux:field>
            </div>
        </flux:card>

        {{-- Driving Information Section --}}
        <flux:card>
            <div>
                <flux:heading size="lg">Driving Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label>Drive Time Purchased</flux:label>
                    <flux:input type="number" step="0.5" wire:model="form.drive_time_purchased" placeholder="0.0" />
                    <flux:error name="form.drive_time_purchased" />
                </flux:field>

                <flux:field>
                    <flux:label>Drive Time Completed</flux:label>
                    <flux:input type="number" step="0.5" wire:model="form.drive_time_completed" placeholder="0.0" />
                    <flux:error name="form.drive_time_completed" />
                </flux:field>

                <flux:field>
                    <flux:label>Zone</flux:label>
                    <flux:select wire:model="form.zone_id" placeholder="Select zone...">
                        <flux:select.option>Select zone...</flux:select.option>
                        @foreach($zones as $zone)
                            <flux:select.option value="{{ $zone->id }}">{{ $zone->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.zone_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Pickup Location</flux:label>
                    <flux:select wire:model="form.pickup_location_id" placeholder="Select pickup location...">
                        <flux:select.option>Select pickup location...</flux:select.option>
                        @foreach($pickupLocations as $location)
                            <flux:select.option value="{{ $location->id }}">{{ $location->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.pickup_location_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Home Pickup</flux:label>
                    <flux:checkbox wire:model="form.home_pickup" label="Allow home pickup" />
                    <flux:error name="form.home_pickup" />
                </flux:field>
            </div>
        </flux:card>



        {{-- Form Actions --}}
        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" href="{{ route('dashboard') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Update Student
            </flux:button>
        </div>
    </form>
</div>
