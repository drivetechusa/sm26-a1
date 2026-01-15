<?php

namespace App\Livewire\Forms;

use App\Models\Employee;
use Livewire\Form;

class EmployeeForm extends Form
{
    public ?Employee $employee = null;

    public ?string $firstname = null;

    public ?string $middlename = null;

    public ?string $lastname = null;

    public ?string $namesuffix = null;

    public ?string $street = null;

    public ?string $street1 = null;

    public ?int $zip_id = null;

    public ?string $phone = null;

    public ?string $secondary_phone = null;

    public ?string $email = null;

    public ?string $dob = null;

    public ?string $hire_date = null;

    public ?string $term_date = null;

    public ?string $dl_license = null;

    public ?string $dl_expire = null;

    public ?string $inst_license = null;

    public ?int $school_id = null;

    public ?string $user_level = null;

    public ?bool $active = true;

    public ?string $cdtp_instructor_number = null;

    public ?string $username = null;

    public ?int $scheduler_id = null;

    public ?int $sched_instructor_id = null;

    public ?int $vehicle_id = null;

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'namesuffix' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street1' => ['nullable', 'string', 'max:255'],
            'zip_id' => ['nullable', 'integer', 'exists:zipcodes,zipcode'],
            'phone' => ['nullable', 'string', 'max:20'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'dob' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'term_date' => ['nullable', 'date'],
            'dl_license' => ['nullable', 'string', 'max:255'],
            'dl_expire' => ['nullable', 'date'],
            'inst_license' => ['nullable', 'string', 'max:255'],
            'school_id' => ['nullable', 'integer'],
            'user_level' => ['nullable', 'string', 'max:255'],
            'active' => ['required', 'boolean'],
            'cdtp_instructor_number' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'scheduler_id' => ['nullable', 'integer'],
            'sched_instructor_id' => ['nullable', 'integer'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
        ];
    }

    public function setEmployee(Employee $employee): void
    {
        $this->employee = $employee;

        $this->firstname = $employee->firstname;
        $this->middlename = $employee->middlename;
        $this->lastname = $employee->lastname;
        $this->namesuffix = $employee->namesuffix;
        $this->street = $employee->street;
        $this->street1 = $employee->street1;
        $this->zip_id = $employee->zip_id;
        $this->phone = $employee->phone;
        $this->secondary_phone = $employee->secondary_phone;
        $this->email = $employee->email;
        $this->dob = $employee->dob?->format('Y-m-d');
        $this->hire_date = $employee->hire_date?->format('Y-m-d');
        $this->term_date = $employee->term_date?->format('Y-m-d');
        $this->dl_license = $employee->dl_license;
        $this->dl_expire = $employee->dl_expire?->format('Y-m-d');
        $this->inst_license = $employee->inst_license;
        $this->school_id = $employee->school_id;
        $this->user_level = $employee->user_level;
        $this->active = $employee->active;
        $this->cdtp_instructor_number = $employee->cdtp_instructor_number;
        $this->username = $employee->username;
        $this->scheduler_id = $employee->scheduler_id;
        $this->sched_instructor_id = $employee->sched_instructor_id;
        $this->vehicle_id = $employee->vehicle_id;
    }

    public function store(): Employee
    {
        $this->validate();

        return Employee::create($this->only([
            'firstname',
            'middlename',
            'lastname',
            'namesuffix',
            'street',
            'street1',
            'zip_id',
            'phone',
            'secondary_phone',
            'email',
            'dob',
            'hire_date',
            'term_date',
            'dl_license',
            'dl_expire',
            'inst_license',
            'school_id',
            'user_level',
            'active',
            'cdtp_instructor_number',
            'username',
            'scheduler_id',
            'sched_instructor_id',
            'vehicle_id',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->employee->update($this->only([
            'firstname',
            'middlename',
            'lastname',
            'namesuffix',
            'street',
            'street1',
            'zip_id',
            'phone',
            'secondary_phone',
            'email',
            'dob',
            'hire_date',
            'term_date',
            'dl_license',
            'dl_expire',
            'inst_license',
            'school_id',
            'user_level',
            'active',
            'cdtp_instructor_number',
            'username',
            'scheduler_id',
            'sched_instructor_id',
            'vehicle_id',
        ]));
    }
}
