<?php

namespace App\Livewire\Forms;

use App\Enums\StudentStatus;
use App\Enums\StudentTypes;
use App\Models\Student;
use Livewire\Form;

class StudentForm extends Form
{
    public ?Student $student = null;

    // Basic Information
    public ?string $stu_web_id = null;

    public ?int $school_id = null;

    public ?string $firstname = null;

    public ?string $middlename = null;

    public ?string $lastname = null;

    public ?string $suffix = null;

    public ?string $goes_by = null;

    public ?string $dob = null;

    public ?string $gender = null;

    public ?string $ssn = null;

    // Contact Information
    public ?string $phone = null;

    public ?string $secondary_phone = null;

    public ?string $email = null;

    public ?string $student_phone = null;

    public ?string $email_student = null;

    // Address Information
    public ?string $street = null;

    public ?string $street1 = null;

    public ?int $zip_id = null;

    public ?string $neighborhood = null;

    // Parent/Guardian Information
    public ?string $parent_name = null;

    public ?string $parent_relationship = null;

    public ?string $parent_name_alternate = null;

    public ?string $parent_alternate_relationship = null;

    public ?string $guardian_2_email = null;

    // School/Education Information
    public ?string $high_school = null;

    public ?int $instructor_id = null;

    // Status & Type
    public ?string $status = null;

    public ?string $type = null;

    // Dates
    public ?string $date_started = null;

    public ?string $date_completed = null;

    // Permit Information
    public ?string $permit_number = null;

    public ?string $issue_date = null;

    public ?string $renewal_date = null;

    public ?bool $permit_verified = null;

    // Driving Information
    public ?float $drive_time_purchased = null;

    public ?float $drive_time_completed = null;

    public ?int $zone_id = null;

    public ?bool $home_pickup = null;

    public ?int $pickup_location_id = null;

    // Other
    public ?string $username = null;

    public ?string $contract = null;

    public function rules(): array
    {
        return [
            // Basic Information
            'stu_web_id' => ['nullable', 'string', 'max:255'],
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'goes_by' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'ssn' => ['nullable', 'string', 'max:11'],

            // Contact Information
            'phone' => ['nullable', 'string', 'max:20'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'student_phone' => ['nullable', 'string', 'max:20'],
            'email_student' => ['nullable', 'email', 'max:255'],

            // Address Information
            'street' => ['nullable', 'string', 'max:255'],
            'street1' => ['nullable', 'string', 'max:255'],
            'zip_id' => ['nullable', 'integer', 'exists:zipcodes,zipcode'],
            'neighborhood' => ['nullable', 'string', 'max:255'],

            // Parent/Guardian Information
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_relationship' => ['nullable', 'string', 'max:255'],
            'parent_name_alternate' => ['nullable', 'string', 'max:255'],
            'parent_alternate_relationship' => ['nullable', 'string', 'max:255'],
            'guardian_2_email' => ['nullable', 'email', 'max:255'],

            // School/Education Information
            'high_school' => ['nullable', 'string', 'max:255'],
            'instructor_id' => ['nullable', 'integer', 'exists:employees,id'],

            // Status & Type
            'status' => ['nullable', 'string', 'in:'.implode(',', array_column(StudentStatus::cases(), 'value'))],
            'type' => ['nullable', 'string', 'in:'.implode(',', array_column(StudentTypes::cases(), 'value'))],

            // Dates
            'date_started' => ['nullable', 'date'],
            'date_completed' => ['nullable', 'date'],

            // Permit Information
            'permit_number' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['nullable', 'date'],
            'renewal_date' => ['nullable', 'date'],
            'permit_verified' => ['nullable', 'boolean'],

            // Driving Information
            'drive_time_purchased' => ['nullable', 'numeric', 'min:0'],
            'drive_time_completed' => ['nullable', 'numeric', 'min:0'],
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
            'home_pickup' => ['nullable', 'boolean'],
            'pickup_location_id' => ['nullable', 'integer', 'exists:pickup_locations,id'],

            // Other
            'username' => ['nullable', 'string', 'max:255'],
            'contract' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function setStudent(Student $student): void
    {
        $this->student = $student;

        // Basic Information
        $this->stu_web_id = $student->stu_web_id;
        $this->school_id = $student->school_id;
        $this->firstname = $student->firstname;
        $this->middlename = $student->middlename;
        $this->lastname = $student->lastname;
        $this->suffix = $student->suffix;
        $this->goes_by = $student->goes_by;
        $this->dob = $student->dob?->format('Y-m-d');
        $this->gender = $student->gender;
        $this->ssn = $student->ssn;

        // Contact Information
        $this->phone = $student->phone;
        $this->secondary_phone = $student->secondary_phone;
        $this->email = $student->email;
        $this->student_phone = $student->student_phone;
        $this->email_student = $student->email_student;

        // Address Information
        $this->street = $student->street;
        $this->street1 = $student->street1;
        $this->zip_id = $student->zip_id;
        $this->neighborhood = $student->neighborhood;

        // Parent/Guardian Information
        $this->parent_name = $student->parent_name;
        $this->parent_relationship = $student->parent_relationship;
        $this->parent_name_alternate = $student->parent_name_alternate;
        $this->parent_alternate_relationship = $student->parent_alternate_relationship;
        $this->guardian_2_email = $student->guardian_2_email;

        // School/Education Information
        $this->high_school = $student->high_school;
        $this->instructor_id = $student->instructor_id;

        // Status & Type
        $this->status = $student->status?->value;
        $this->type = $student->type?->value;

        // Dates
        $this->date_started = $student->date_started?->format('Y-m-d');
        $this->date_completed = $student->date_completed?->format('Y-m-d');

        // Permit Information
        $this->permit_number = $student->permit_number;
        $this->issue_date = $student->issue_date?->format('Y-m-d');
        $this->renewal_date = $student->renewal_date?->format('Y-m-d');
        $this->permit_verified = $student->permit_verified;

        // Driving Information
        $this->drive_time_purchased = $student->drive_time_purchased;
        $this->drive_time_completed = $student->drive_time_completed;
        $this->zone_id = $student->zone_id;
        $this->home_pickup = $student->home_pickup;
        $this->pickup_location_id = $student->pickup_location_id;

        // Other
        $this->username = $student->username;
        $this->contract = $student->contract;
    }

    public function store(): Student
    {
        $this->validate();

        return Student::create($this->only([
            'stu_web_id', 'school_id', 'firstname', 'middlename', 'lastname', 'suffix',
            'street', 'street1', 'zip_id', 'phone', 'secondary_phone', 'email', 'dob',
            'status', 'type', 'date_started', 'date_completed', 'permit_number',
            'issue_date', 'renewal_date', 'zone_id', 'home_pickup', 'ssn', 'username',
            'drive_time_purchased', 'drive_time_completed', 'permit_verified', 'contract',
            'high_school', 'email_student', 'parent_name', 'parent_name_alternate',
            'student_phone', 'gender', 'goes_by', 'pickup_location_id', 'guardian_2_email',
            'neighborhood', 'instructor_id', 'parent_relationship', 'parent_alternate_relationship',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->student->update($this->only([
            'stu_web_id', 'school_id', 'firstname', 'middlename', 'lastname', 'suffix',
            'street', 'street1', 'zip_id', 'phone', 'secondary_phone', 'email', 'dob',
            'status', 'type', 'date_started', 'date_completed', 'permit_number',
            'issue_date', 'renewal_date', 'zone_id', 'home_pickup', 'ssn', 'username',
            'drive_time_purchased', 'drive_time_completed', 'permit_verified', 'contract',
            'high_school', 'email_student', 'parent_name', 'parent_name_alternate',
            'student_phone', 'gender', 'goes_by', 'pickup_location_id', 'guardian_2_email',
            'neighborhood', 'instructor_id', 'parent_relationship', 'parent_alternate_relationship',
        ]));
    }
}
