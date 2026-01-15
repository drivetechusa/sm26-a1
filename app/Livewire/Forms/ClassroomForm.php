<?php

namespace App\Livewire\Forms;

use App\Models\Classroom;
use Livewire\Form;

class ClassroomForm extends Form
{
    public ?Classroom $classroom = null;

    public ?string $name = null;

    public ?string $street = null;

    public ?string $street1 = null;

    public ?int $zip_id = null;

    public ?string $directions = null;

    public ?bool $active = true;

    public ?float $teen_price = null;

    public ?float $ext_price = null;

    public ?float $adv_price = null;

    public ?float $lxl_price = null;

    public ?float $point_price = null;

    public ?float $latecancelfee = null;

    public ?float $dmvfee = null;

    public ?float $noshowfee = null;

    public ?float $registrationfee = null;

    public ?int $Capacity = null;

    public ?float $permit_test_price = null;

    public ?float $road_test_price = null;

    public ?float $evaluation_price = null;

    public ?float $instructor_course_price = null;

    public ?float $hand_controls_price = null;

    public ?float $insurance_price = null;

    public ?float $lxl_discount_price = null;

    public ?int $school_id = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'street1' => ['nullable', 'string', 'max:255'],
            'zip_id' => ['required', 'integer', 'exists:zipcodes,zipcode'],
            'directions' => ['nullable', 'string'],
            'active' => ['required', 'boolean'],
            'teen_price' => ['nullable', 'numeric', 'min:0'],
            'ext_price' => ['nullable', 'numeric', 'min:0'],
            'adv_price' => ['nullable', 'numeric', 'min:0'],
            'lxl_price' => ['nullable', 'numeric', 'min:0'],
            'point_price' => ['nullable', 'numeric', 'min:0'],
            'latecancelfee' => ['nullable', 'numeric', 'min:0'],
            'dmvfee' => ['nullable', 'numeric', 'min:0'],
            'noshowfee' => ['nullable', 'numeric', 'min:0'],
            'registrationfee' => ['nullable', 'numeric', 'min:0'],
            'Capacity' => ['nullable', 'integer', 'min:0'],
            'permit_test_price' => ['nullable', 'numeric', 'min:0'],
            'road_test_price' => ['nullable', 'numeric', 'min:0'],
            'evaluation_price' => ['nullable', 'numeric', 'min:0'],
            'instructor_course_price' => ['nullable', 'numeric', 'min:0'],
            'hand_controls_price' => ['nullable', 'numeric', 'min:0'],
            'insurance_price' => ['nullable', 'numeric', 'min:0'],
            'lxl_discount_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function setClassroom(Classroom $classroom): void
    {
        $this->classroom = $classroom;

        $this->school_id = config('app.school_id');
        $this->name = $classroom->name;
        $this->street = $classroom->street;
        $this->street1 = $classroom->street1;
        $this->zip_id = $classroom->zip_id;
        $this->directions = $classroom->directions;
        $this->active = $classroom->active;
        $this->teen_price = $classroom->teen_price;
        $this->ext_price = $classroom->ext_price;
        $this->adv_price = $classroom->adv_price;
        $this->lxl_price = $classroom->lxl_price;
        $this->point_price = $classroom->point_price;
        $this->latecancelfee = $classroom->latecancelfee;
        $this->dmvfee = $classroom->dmvfee;
        $this->noshowfee = $classroom->noshowfee;
        $this->registrationfee = $classroom->registrationfee;
        $this->Capacity = $classroom->Capacity;
        $this->permit_test_price = $classroom->permit_test_price;
        $this->road_test_price = $classroom->road_test_price;
        $this->evaluation_price = $classroom->evaluation_price;
        $this->instructor_course_price = $classroom->instructor_course_price;
        $this->hand_controls_price = $classroom->hand_controls_price;
        $this->insurance_price = $classroom->insurance_price;
        $this->lxl_discount_price = $classroom->lxl_discount_price;
    }

    public function store(): Classroom
    {
        $this->validate();

        return Classroom::create($this->only([
            'school_id',
            'name',
            'street',
            'street1',
            'zip_id',
            'directions',
            'active',
            'teen_price',
            'ext_price',
            'adv_price',
            'lxl_price',
            'point_price',
            'latecancelfee',
            'dmvfee',
            'noshowfee',
            'registrationfee',
            'Capacity',
            'permit_test_price',
            'road_test_price',
            'evaluation_price',
            'instructor_course_price',
            'hand_controls_price',
            'insurance_price',
            'lxl_discount_price',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->classroom->update($this->only([
            'name',
            'street',
            'street1',
            'zip_id',
            'directions',
            'active',
            'teen_price',
            'ext_price',
            'adv_price',
            'lxl_price',
            'point_price',
            'latecancelfee',
            'dmvfee',
            'noshowfee',
            'registrationfee',
            'Capacity',
            'permit_test_price',
            'road_test_price',
            'evaluation_price',
            'instructor_course_price',
            'hand_controls_price',
            'insurance_price',
            'lxl_discount_price',
        ]));
    }
}
